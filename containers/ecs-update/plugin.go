package main

import (
	//"mime"
	//"os"
	"fmt"
	//"path/filepath"
	//"strings"

	//"errors"
	//log "github.com/Sirupsen/logrus"
	"github.com/aws/aws-sdk-go/aws"
	"github.com/aws/aws-sdk-go/aws/credentials"
	"github.com/aws/aws-sdk-go/aws/session"
	"github.com/aws/aws-sdk-go/service/ecs"
	"github.com/aws/aws-sdk-go/service/ecs/ecsiface"
	//"github.com/mattn/go-zglob"
)

// Plugin defines the ECS update plugin parameters.
type Plugin struct {
	Cluster string
	Key      string
	Secret   string
	Service  string

	Region string

	Client ecsiface.ECSAPI
}

func NewPlugin(accessKey string, secretKey string, cluster string, service string, region string) *Plugin {

	// create the client
	conf := &aws.Config{
		Region:           aws.String(region),
	}

	//Allowing to use the instance role or provide a key and secret
	if accessKey != "" && secretKey != "" {
		conf.Credentials = credentials.NewStaticCredentials(accessKey, secretKey, "")
	}
	client := ecs.New(session.New(), conf)

	return &Plugin{
		Key:          accessKey,
		Secret:       secretKey,
		Cluster:       cluster,
		Service:      service,		
		Region:       region,
		Client:		client,
	}
}

// Exec runs the plugin
func (p *Plugin) Exec() error {

	taskDefinition, err := p.getTaskDefinitionFromService();

	if err != nil {
		fmt.Println("Error", err);
		return nil
	}

	taskDefinitionArn, err := p.updateTaskDefinition(taskDefinition);

	if err != nil {
		fmt.Println("Error", err);
		return nil
	}

	err = p.updateService(taskDefinitionArn);

	if err != nil {
		fmt.Println("Error", err);
		return nil
	}

	fmt.Println("Completed");

	return nil
}

func (p *Plugin) wait() error {
	params := &ecs.DescribeServicesInput{
		Services: []*string{
			aws.String(p.Service),
		},
		Cluster: aws.String(p.Cluster),
	}

	return p.Client.WaitUntilServicesStable(params)
}

func (p *Plugin) updateService(taskDefinitionArn string) error {

	params := &ecs.UpdateServiceInput{
		Service:                 aws.String(p.Service),
		Cluster:                 aws.String(p.Cluster),
		TaskDefinition:          aws.String(taskDefinitionArn),
	}

	resp, err := p.Client.UpdateService(params)

	if err != nil {
		return err
	}

	service := resp.Service
	if *service.DesiredCount <= 0 {
		return nil
	}

	//return p.wait();
	return nil
}

func (p *Plugin) updateTaskDefinition(taskDefinition *ecs.TaskDefinition) (string, error) {
	
	params := &ecs.RegisterTaskDefinitionInput{
		ContainerDefinitions: taskDefinition.ContainerDefinitions,
		Family:               taskDefinition.Family,
		NetworkMode:          taskDefinition.NetworkMode,
		PlacementConstraints: taskDefinition.PlacementConstraints,
		TaskRoleArn:          taskDefinition.TaskRoleArn,
		Volumes:              taskDefinition.Volumes,
	}
	
	resp, err := p.Client.RegisterTaskDefinition(params);
	
	if err != nil {
		fmt.Println("Error", err);
		return "", err
	}

	taskDefinitionArn := *resp.TaskDefinition.TaskDefinitionArn;
	return taskDefinitionArn, nil
}

func (p *Plugin) getTaskDefinitionFromService() (*ecs.TaskDefinition, error) {

	// GET THE SERVICE
	params := &ecs.DescribeServicesInput{
		Services: []*string{
			aws.String(p.Service),
		},
		Cluster: aws.String(p.Cluster),
	}

	resp, err := p.Client.DescribeServices(params)

	if err != nil {
		fmt.Println("Error", err);
		return nil, err
	}

	taskDefinitionArn := *resp.Services[0].Deployments[0].TaskDefinition;

	taskDefinition, err := p.getTaskDefinitionFromArn(taskDefinitionArn)

	return taskDefinition, err;
}

func (p *Plugin) getTaskDefinitionFromArn(taskDefinitionArn string) (*ecs.TaskDefinition, error) {
	
	//get the task definition
	params := &ecs.DescribeTaskDefinitionInput{
		TaskDefinition: aws.String(taskDefinitionArn),
	}

	resp, err := p.Client.DescribeTaskDefinition(params)
	
	if err != nil {
		fmt.Println("Error", err);
		return nil, err
	}

	return resp.TaskDefinition, nil;

}