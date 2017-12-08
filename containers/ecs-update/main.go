package main

import (
	"fmt"
	"os"

	"github.com/joho/godotenv"
	"github.com/urfave/cli"
)

var build = "0" // build number set at compile-time

func main() {
	app := cli.NewApp()
	app.Name = "ecs update plugin"
	app.Usage = "ecs update plugin"
	app.Action = run
	app.Version = fmt.Sprintf("1.0.%s", build)
	app.Flags = []cli.Flag{
		cli.StringFlag{
			Name:   "access-key",
			Usage:  "aws access key",
			EnvVar: "PLUGIN_ACCESS_KEY,AWS_ACCESS_KEY_ID",
		},
		cli.StringFlag{
			Name:   "secret-key",
			Usage:  "aws secret key",
			EnvVar: "PLUGIN_SECRET_KEY,AWS_SECRET_ACCESS_KEY",
		},
		cli.StringFlag{
			Name:   "cluster",
			Usage:  "ecs cluster name (or arn)",
			EnvVar: "PLUGIN_CLUSTER,ECS_CLUSTER",
		},
		cli.StringFlag{
			Name:   "region",
			Usage:  "aws region",
			Value:  "us-east-1",
			EnvVar: "PLUGIN_REGION,ECS_REGION",
		},
		cli.StringFlag{
			Name:   "service",
			Usage:  "service to update",
			EnvVar: "PLUGIN_SERVICE,ECS_SERVICE",
		},
		cli.BoolTFlag{
			Name:   "yaml-verified",
			Usage:  "Ensure the yaml was signed",
			EnvVar: "DRONE_YAML_VERIFIED",
		},
		cli.StringFlag{
			Name:  "env-file",
			Usage: "source env file",
		},
	}

	if err := app.Run(os.Args); err != nil {
		fmt.Println("Error", err);
	}
}

func run(c *cli.Context) error {
	if c.String("env-file") != "" {
		_ = godotenv.Load(c.String("env-file"))
	}

	plugin := NewPlugin(
		c.String("access-key"),
		c.String("secret-key"),
		c.String("cluster"),
		c.String("service"),		
		c.String("region"),
	)

	return plugin.Exec()
}
