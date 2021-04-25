<?php
/*
#############################################################################################################
#                                                                                                           #
#   This is very simple class to use basic functionality of zoom api and video conferancing                 #                                                                                             
#   author : subho biswas                                                                                   #
#   You are free to use and modify this code                                                                #
#   If you have any query comment                                                                           #
#                                                                                                           #
#############################################################################################################
*/

use Firebase\JWT\JWT;
use GuzzleHttp\Client;

require_once 'vendor/autoload.php';
//this is the main class which hass all the function of zoom metting
class zoom extends JWT
{
    private $ZOOM_API_KEY = 'YOUR_ZOOM_API_KEY';
    private $ZOOM_SECRET_KEY = 'YOUR_ZOOM_SECRAT_KEY';
    //this function get the jWT access token from zoom 
    function getZoomAccessToken()
    {

        $key = $this->ZOOM_SECRET_KEY;
        $payload = array(
            "iss" => $this->ZOOM_API_KEY,
            'exp' => time() + 3600,
        );
        return JWT::encode($payload, $key);
    }
    //this function allow you to create a new zoom meeting
    function CreateMeeting($topic, $type, $start_time, $duration,$timezone, $password)
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.zoom.us',
        ]);

        $response = $client->request('POST', '/v2/users/me/meetings', [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken()
            ],
            'json' => [
                "topic" => $topic,
                "type" => $type,
                "start_time" => $start_time,
                "timezone" => $timezone,
                "duration" => $duration,
                "password" => $password
            ],
        ]);

        $data = json_decode($response->getBody());
        $result = array(
            'id' => $data->id,
            'start_url' => $data->start_url,
            'password' => $data->password,
            'topic' => $data->topic,
            'start_time' => $data->start_time,
            'duration' => $data->duration,
            'timezone' => $data->timezone,
            'join_url' => $data->join_url
        );
        // this array retunr the all info about new meeting
        return $result;
    }
    // this function allow you edit meeting info that you created by id
    function UpdateMeeting($meeting_id, $topic, $type, $start_time, $duration, $password)
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.zoom.us',
        ]);

        $response = $client->request("PATCH", "/v2/meetings/$meeting_id", [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken()
            ],
            'json' => [
                "topic" => $topic,
                "type" => $type,
                "start_time" => $start_time,
                "timezone" => "Asia/Kolkata",
                "duration" => $duration,
                "password" => $password
            ],
        ]);
        if (204 == $response->getStatusCode()) {
            $result = array("message" => "Meeting updated successfully id = " . $meeting_id);
        } else {
            $result = array("message" => "Meeting Id not found or Somthing Went wrong");
        }
        return $result;
    }
    // this function allow you delete the meeting you created by meeting id
    function DeleteMeeting($meeting_id)
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.zoom.us',
        ]);

        $response = $client->request("DELETE", "/v2/meetings/$meeting_id", [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken()
            ]
        ]);

        if (204 == $response->getStatusCode()) {
            $result = array("message" => "Meeting deleted successfully id = " . $meeting_id);
        } else {
            $result = array("message" => "Meeting Id not found or Somthing Went wrong");
        }
        return $result;
    }
    // this function return the list of meeting you created
    public function ListMeeting()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);
        $response = $client->request('GET', '/v2/users/me/meetings', [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken()
            ]
        ]);

        $data = json_decode($response->getBody());
        if (empty($data)) {
            $result = array('message' => 'No meeting Found');
        } else {
            $result = array();
            foreach ($data->meetings as $value) {
                array_push(
                    $result,
                    array(
                        'id' => $value->id,
                        'topic' => $value->topic,
                        'type' => $value->type,
                        'start_time' => $value->start_time,
                        'duration' => $value->duration,
                        'timezone' => $value->timezone,
                        'created_at' => $value->created_at,
                        'join_url' => $value->join_url
                    )
                );
            }
        }
        //this array will return all the list of meeting that you created in your account
        return $result;
    }
    //this function need paid for zoom api it returns details of past meeting
    public function MeetingDetail($meeting_id)
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);
        $response = $client->request("GET", "/v2/report/meetings/$meeting_id", [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken()
            ]
        ]);

        $data = json_decode($response->getBody());
        if (empty($data)) {
            $result = array('message' => 'id not match or somthing went wrong');
        } else {
            $result = $data;
        }
        //this array will return the details of meeting by id
        return $result;
    }
    //this function need paid for zoom api it returns all the participants list that attend the meetings
    public function ParticipantsDetails($meeting_id)
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

        $response = $client->request("GET", "/v2/past_meetings/$meeting_id/participants", [
            "headers" => [
                "Authorization" => "Bearer " . $this->getZoomAccessToken()
            ]
        ]);

        $data = json_decode($response->getBody());
        if (empty($data)) {
            $result = array('message' => 'No report found in this account');
        } else {
            $result = array();
            foreach ($data->participants as $p) {
                array_push(
                    $result,
                    array(
                        'name' => $p->name,
                        'email' => $p->user_email
                    )
                );
            }
        }
        //this array return all the participants name and email that participate the meeting
        return $result;
    }
}
