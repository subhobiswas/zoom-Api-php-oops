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
include 'zoom_class.php';
$zoom=new zoom();

// CREATE MEETING EXAMPLE
$TOPIC='THIS IS ZOOM MEETING TOPIC';
$TYPE='2';//INTEGER
$START_TIME='2021-04-26T10:10:00';
$DURATION='60';//MINUTE
$TIMEZONE='Asia/Kolkata';
$PASSWORD='12345678';

$new_meeting=$zoom->CreateMeeting($TOPIC,$TYPE,$START_TIME,$DURATION,$TIMEZONE,$PASSWORD);
echo '<pre>';
print_r($new_meeting);
echo '</pre>';

// // UPDATE MEETING EXAMPLE

// $MEETING_ID='YOUR_MEETING_ID';
// $TOPIC='THIS IS ZOOM MEETING TOPIC';
// $TYPE='2';//INTEGER
// $START_TIME='2021-04-26T10:10:00';
// $DURATION='60';//MINUTE
// $TIMEZONE='Asia/Kolkata';
// $PASSWORD='12345678';
// $update_meeting=$zoom->UpdateMeeting($MEETING_ID,$TOPIC,$TYPE,$START_TIME,$DURATION,$TIMEZONE,$PASSWORD);
// echo '<pre>';
// print_r($update_meeting);
// echo '</pre>';

// // DELETE MEETING EXAMPLE

// $MEETING_ID='YOUR_MEETING_ID';
// $delete_meeting=$zoom->DeleteMeeting($MEETING_ID);


// //LIST OF ALL METTING EXAMPLE

// $list_meeting=$zoom->ListMeeting();
// echo '<pre>';
// print_r($list_meeting);
// echo '</pre>';

// // GETTING DETAILS OF PAST MEETING BY ID

// $MEETING_ID='YOUR_MEETING_ID';
// $details_meeting=$zoom->MeetingDetail('MEETING_ID');
// echo '<pre>';
// print_r($details_meeting);
// echo '</pre>';



//////////////////////////////  ALSO CHECK THE CLASS USE ALL FUNCTION  //////////////////////////////