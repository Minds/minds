<?php
/**
*
* A PHP class that acts as wrapper for Asana API. Lets make things easy! :)
*
* Read Asana API documentation for fully use this class http://developer.asana.com/documentation/
*
* Copyright 2012 Ajimix
* Licensed under the Apache License 2.0
*
* Author: Ajimix [github.com/ajimix]
* Version: 1.0
*
*/
class Asana {

    private $timeout = 10;
    private $debug = false;
    private $advDebug = false; // Note that enabling advanced debug will include debugging information in the response possibly breaking up your code
    private $asanaApiVersion = "1.0";

    public $responseCode;

    private $endPointUrl;
    private $apiKey;
    private $taskUrl;
    private $userUrl;
    private $projectsUrl;
    private $workspaceUrl;
    private $storiesUrl;
    private $tagsUrl;

    public function __construct($apiKey){
        if(substr($apiKey, -1) != ":") $apiKey .= ":"; // If the API key is not ended by ":", we append it
        $this->apiKey = $apiKey;

        $this->endPointUrl = "https://app.asana.com/api/{$this->asanaApiVersion}/";
        $this->taskUrl = $this->endPointUrl."tasks";
        $this->userUrl = $this->endPointUrl."users";
        $this->projectsUrl = $this->endPointUrl."projects";
        $this->workspaceUrl = $this->endPointUrl."workspaces";
        $this->storiesUrl = $this->endPointUrl."stories";
        $this->tagsUrl = $this->endPointUrl."tags";

        define("METHOD_POST", 1);
        define("METHOD_PUT", 2);
        define("METHOD_GET", 3);
    }


    /**
     * **********************************
     * User functions
     * **********************************
     */

    /**
     * Returns the full user record for a single user.
     * Call it without parameters to get the users info of the owner of the API key.
     *
     * @param string $userId
     * @return string JSON or null
     */
    public function getUserInfo($userId = null){
        if(is_null($userId)) $userId = "me";
        return $this->askAsana($this->userUrl."/{$userId}");
    }

    /**
     * Returns the user records for all users in all workspaces you have access.
     *
     * @return string JSON or null
     */
    public function getUsers(){
        return $this->askAsana($this->userUrl);
    }


    /**
     * **********************************
     * Task functions
     * **********************************
     */

    /**
     * Function to create a task.
     * For assign or remove the task to a project, use the addProjectToTask and removeProjectToTask.
     *
     *
     * @param array $data Array of data for the task following the Asana API documentation.
     * Example:
     *
     * array(
     *     "workspace" => "1768",
     *     "name" => "Hello World!",
     *     "notes" => "This is a task for testing the Asana API :)",
     *     "assignee" => "176822166183",
     *     "followers" => array(
     *         "37136",
     *         "59083"
     *     )
     * )
     *
     * @return string JSON or null
     */
    public function createTask($data){
        $data = array("data" => $data);
        $data = json_encode($data);
        return $this->askAsana($this->taskUrl, $data, METHOD_POST);
    }

    /**
     * Returns task information
     *
     * @param string $taskId
     * @return string JSON or null
     */
    public function getTask($taskId){
        return $this->askAsana($this->taskUrl."/{$taskId}");
    }
    
    /**
     * Returns sub-task information
     *
     * @param string $taskId
     * @return string JSON or null
     */
    public function getSubTasks($taskId){
    	return $this->askAsana($this->taskUrl."/{$taskId}/subtasks");
    }

    /**
     * Updates a task
     *
     * @param string $taskId
     * @param array $data See, createTask function comments for proper parameter info.
     * @return string JSON or null
     */
    public function updateTask($taskId, $data){
        $data = array("data" => $data);
        $data = json_encode($data);
        return $this->askAsana($this->taskUrl."/{$taskId}", $data, METHOD_PUT);
    }

    /**
     * Returns the projects associated to the task.
     *
     * @param string $taskId
     * @return string JSON or null
     */
    public function getProjectsForTask($taskId){
        return $this->askAsana($this->taskUrl."/{$taskId}/projects");
    }

    /**
     * Adds a project to task. If successful, will return success and an empty data block.
     *
     * @param string $taskId
     * @param string $projectId
     * @return string JSON or null
     */
    public function addProjectToTask($taskId, $projectId){
        $data = array("data" => array("project" => $projectId));
        $data = json_encode($data);
        return $this->askAsana($this->taskUrl."/{$taskId}/addProject", $data, METHOD_POST);
    }

    /**
     * Removes project from task. If successful, will return success and an empty data block.
     *
     * @param string $taskId
     * @param string $projectId
     * @return string JSON or null
     */
    public function removeProjectToTask($taskId, $projectId){
        $data = array("data" => array("project" => $projectId));
        $data = json_encode($data);
        return $this->askAsana($this->taskUrl."/{$taskId}/removeProject", $data, METHOD_POST);
    }

    /**
     * Returns task by a given filter.
     * For now (limited by Asana API), you may limit your query either to a specific project or to an assignee and workspace
     *
     * NOTE: As Asana API says, if you filter by assignee, you MUST specify a workspaceId and viceversa.
     *
     * @param array $filter The filter with optional values.
     *
     * array(
     *     "assignee" => "",
     *     "project" => 0,
     *     "workspace" => 0
     * )
     *
     * @return string JSON or null
     */
    public function getTasksByFilter($filter = array("assignee" => "", "project" => "", "workspace" => "")){
        $url = "";
        $filter = array_merge(array("assignee" => "", "project" => "", "workspace" => ""), $filter);
        $url .= $filter["assignee"] != ""?"&assignee={$filter["assignee"]}":"";
        $url .= $filter["project"] != ""?"&project={$filter["project"]}":"";
        $url .= $filter["workspace"] != ""?"&workspace={$filter["workspace"]}":"";
        if(strlen($url) > 0) $url = "?".substr($url, 1);

        return $this->askAsana($this->taskUrl.$url);
    }

    /**
     * Returns the list of stories associated with the object.
     * As usual with queries, stories are returned in compact form.
     * However, the compact form for stories contains more information by default than just the ID.
     * There is presently no way to get a filtered set of stories.
     *
     * @param string $taskId
     * @return string JSON or null
     */
    public function getTaskStories($taskId){
        return $this->askAsana($this->taskUrl."/{$taskId}/stories");
    }

    /**
     * Adds a comment to a task.
     * The comment will be authored by the authorized user, and timestamped when the server receives the request.
     *
     * @param string $taskId
     * @param string $text
     * @return string JSON or null
     */
    public function commentOnTask($taskId, $text = ""){
        $data = array(
            "data" => array(
                "text" => $text
            )
        );
        $data = json_encode($data);
        return $this->askAsana($this->taskUrl."/{$taskId}/stories", $data, METHOD_POST);
    }

    /**
     * Adds a tag to a task. If successful, will return success and an empty data block.
     *
     * @param string $taskId
     * @param string $tagId
     * @return string JSON or null
     */
    public function addTagToTask($taskId, $tagId){
        $data = array("data" => array("tag" => $tagId));
        $data = json_encode($data);
        return $this->askAsana($this->taskUrl."/{$taskId}/addTag", $data, METHOD_POST);
    }

    /**
     * Removes a tag from a task. If successful, will return success and an empty data block.
     *
     * @param string $taskId
     * @param string $tagId
     * @return string JSON or null
     */
    public function removeTagFromTask($taskId, $tagId){
        $data = array("data" => array("tag" => $tagId));
        $data = json_encode($data);
        return $this->askAsana($this->taskUrl."/{$taskId}/removeTag", $data, METHOD_POST);
    }


    /**
     * **********************************
     * Projects functions
     * **********************************
     */

    /**
     * Function to create a project.
     *
     * @param array $data Array of data for the project following the Asana API documentation.
     * Example:
     *
     * array(
     *     "workspace" => "1768",
     *     "name" => "Foo Project!",
     *     "notes" => "This is a test project"
     * )
     *
     * @return string JSON or null
     */
    public function createProject($data){
        $data = array("data" => $data);
        $data = json_encode($data);
        return $this->askAsana($this->projectsUrl, $data, METHOD_POST);
    }

    /**
     * Returns the full record for a single project.
     *
     * @param string $projectId
     * @return string JSON or null
     */
    public function getProject($projectId){
        return $this->askAsana($this->projectsUrl."/{$projectId}");
    }

    /**
     * Returns the projects in all workspaces containing archived ones or not.
     *
     * @param boolean $archived Return archived projects or not
     * @param string  $opt_fields Return results with optional parameters
     */
    public function getProjects($archived = false, $opt_fields = ""){
        $archived = $archived?"true":"false";
        $opt_fields = ($opt_fields != "")?"&opt_fields={$opt_fields}":"";
        return $this->askAsana($this->projectsUrl."?archived={$archived}{$opt_fields}");
    }

    /**
     * Returns the projects in provided workspace containing archived ones or not.
     *
     * @param string $workspaceId
     * @param boolean $archived Return archived projects or not
     */
    public function getProjectsInWorkspace($workspaceId, $archived = false){
        $archived = $archived?"true":"false";
        return $this->askAsana($this->projectsUrl."?archived={$archived}&workspace={$workspaceId}");
    }

    /**
     * This method modifies the fields of a project provided in the request, then returns the full updated record.
     *
     * @param string $projectId
     * @param array $data An array containing fields to update, see Asana API if needed.
     * Example: array("name" => "Test", "notes" => "It's a test project");
     *
     * @return string JSON or null
     */
    public function updateProject($projectId, $data){
        $data = array("data" => $data);
        $data = json_encode($data);
        return $this->askAsana($this->projectsUrl."/{$projectId}", $data, METHOD_PUT);
    }

    /**
     * Returns all unarchived tasks of a given project
     *
     * @param string $projectId
     *
     * @return string JSON or null
     */
    public function getProjectTasks($projectId){
        return $this->askAsana($this->taskUrl."?project={$projectId}");
    }

    /**
     * Returns the list of stories associated with the object.
     * As usual with queries, stories are returned in compact form.
     * However, the compact form for stories contains more
     * information by default than just the ID.
     * There is presently no way to get a filtered set of stories.
     *
     * @param string $projectId
     * @return string JSON or null
     */
    public function getProjectStories($projectId){
        return $this->askAsana($this->projectsUrl."/{$projectId}/stories");
    }

    /**
     * Adds a comment to a project
     * The comment will be authored by the authorized user, and timestamped when the server receives the request.
     *
     * @param string $projectId
     * @param string $text
     * @return string JSON or null
     */
    public function commentOnProject($projectId, $text = ""){
        $data = array(
            "data" => array(
                "text" => $text
            )
        );
        $data = json_encode($data);
        return $this->askAsana($this->projectsUrl."/{$projectId}/stories", $data, METHOD_POST);
    }


    /**
     * **********************************
     * Tags functions
     * **********************************
     */

    /**
     * Returns the full record for a single tag.
     *
     * @param string $tagId
     * @return string JSON or null
     */
    public function getTag($tagId){
        return $this->askAsana($this->tagsUrl."/{$tagId}");
    }

    /**
     * Returns the full record for all tags in all workspaces.
     *
     * @return string JSON or null
     */
    public function getTags(){
        return $this->askAsana($this->tagsUrl);
    }

    /**
     * Modifies the fields of a tag provided in the request, then returns the full updated record.
     *
     * @param string $tagId
     * @param array $data An array containing fields to update, see Asana API if needed.
     * Example: array("name" => "Test", "notes" => "It's a test tag");
     *
     * @return string JSON or null
     */
    public function updateTag($tagId, $data){
        $data = array("data" => $data);
        $data = json_encode($data);
        return $this->askAsana($this->tagsUrl."/{$tagId}", $data, METHOD_PUT);
    }

    /**
     * Returns the list of all tasks with this tag. Tasks can have more than one tag at a time.
     *
     * @param string $tagId
     * @return string JSON or null
     */
    public function getTasksWithTag($tagId){
        return $this->askAsana($this->tagsUrl."/{$tagId}/tasks");
    }


    /**
     * **********************************
     * Stories and comments functions
     * **********************************
     */

    /**
     * Returns the full record for a single story.
     *
     * @param string $storyId
     * @return string JSON or null
     */
    public function getSingleStory($storyId){
        return $this->askAsana($this->storiesUrl."/{$storyId}");
    }


    /**
     * **********************************
     * Workspaces functions
     * **********************************
     */

    /**
     * Returns all the workspaces.
     *
     * @return string JSON or null
     */
    public function getWorkspaces(){
        return $this->askAsana($this->workspaceUrl);
    }

    /**
     * Currently the only field that can be modified for a workspace is its name (as Asana API says).
     * This method returns the complete updated workspace record.
     *
     * @param array $data
     * Example: array("name" => "Test");
     *
     * @return string JSON or null
     */
    public function updateWorkspace($workspaceId, $data = array("name" => "")){
        $data = array("data" => $data);
        $data = json_encode($data);
        return $this->askAsana($this->workspaceUrl."/{$workspaceId}", $data, METHOD_PUT);
    }

    /**
     * Returns tasks of all workspace assigned to someone.
     * Note: As Asana API says, you must specify an assignee when querying for workspace tasks.
     *
     * @param string $workspaceId The id of the workspace
     * @param string $assignee Can be "me" or user ID
     *
     * @return string JSON or null
     */
    public function getWorkspaceTasks($workspaceId, $assignee = "me"){
        return $this->askAsana($this->taskUrl."?workspace={$workspaceId}&assignee={$assignee}");
    }

    /**
     * Returns tags of all workspace.
     *
     * @param string $workspaceId The id of the workspace
     *
     * @return string JSON or null
     */
    public function getWorkspaceTags($workspaceId){
        return $this->askAsana($this->workspaceUrl."/{$workspaceId}/tags");
    }

    /**
     * Returns users of all workspace.
     *
     * @param string $workspaceId The id of the workspace
     *
     * @return string JSON or null
     */
    public function getWorkspaceUsers($workspaceId){
        return $this->askAsana($this->workspaceUrl."/{$workspaceId}/users");
    }

    /**
     * This function communicates with Asana REST API.
     * You don't need to call this function directly. It's only for inner class working.
     *
     * @param string $url
     * @param string $data Must be a json string
     * @param int $method See constants defined at the beginning of the class
     * @return string JSON or null
     */
    private function askAsana($url, $data = null, $method = METHOD_GET){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Don't print the result
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // Don't verify SSL connection
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); //         ""           ""
        curl_setopt($curl, CURLOPT_USERPWD, $this->apiKey);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); // Send as JSON
        if($this->advDebug){
            curl_setopt($curl, CURLOPT_HEADER, true); // Display headers
            curl_setopt($curl, CURLOPT_VERBOSE, true); // Display communication with server
        }
        if($method == METHOD_POST){
            curl_setopt($curl, CURLOPT_POST, true);
        } else if($method == METHOD_PUT){
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        }
        if(!is_null($data) && ($method == METHOD_POST || $method == METHOD_PUT)){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        try {
            $return = curl_exec($curl);
            $this->responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if($this->debug || $this->advDebug){
                echo "<pre>"; print_r(curl_getinfo($curl)); echo "</pre>";
            }
        } catch(Exception $ex){
            if($this->debug || $this->advDebug){
                echo "<br>cURL error num: ".curl_errno($curl);
                echo "<br>cURL error: ".curl_error($curl);
            }
            echo "Error on cURL";
            $return = null;
        }

        curl_close($curl);

        return $return;
    }
}