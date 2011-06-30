<?php
/**
 * Perfrorms permissions checks against the user to see what they are allowed to
 * do, which are stored as boolean values in local variables.
 *
 * @copyright &copy; 2011 University of London Computer Centre
 * @author http://www.ulcc.ac.uk, http://moodle.ulcc.ac.uk
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package ILP
 * @version 2.0
 */

global $CFG, $PARSER,$USER;

//get the user id if it is not set then we will pass the global $USER->id 
$user_id   = $PARSER->optional_param('user_id',$USER->id,PARAM_INT);

// get the id of the course
$course_id = $PARSER->optional_param('course_id', SITEID,PARAM_INT);


// the user must be logged in
require_login(0, false);

$sitecontext	=	get_context_instance(CONTEXT_SYSTEM);

//get the user context
$usercontext	=	get_context_instance(CONTEXT_USER,$USER->id);

//if there is no user context then we must throw an error as the user context is the 
//least that is needed in order to display the ilp
if (empty($usercontext)) {
	print_error('useridisincorrect', 'block_ilp');
}

//if the course id is set then we can get the course context
if (!empty($course_id)) {
	
	// get the current course context
	$coursecontext = get_context_instance(CONTEXT_COURSE, $course_id);

	// bail if we couldn't find the course context
	if(!$coursecontext) {
	    print_error('incorrectcourseid', 'block_ilp');
	}
}

//by default we will be in the sitecontext
$context	=	$sitecontext;

//if we are in the coursecontext
if(isset($coursecontext)){
	$context		=	$coursecontext;
} else  if (has_capability('block/ilp:viewotherilp', $usercontext,$user_id)) {
	$context		=	$usercontext;	
} else if ($user_id == $USER->id) {
	$context		=	$sitecontext;
} 

//CAPABILITIES
$access_createreports	=	has_capability('block/ilp:addreport', $context);
$access_editreports		=	has_capability('block/ilp:editreport', $context);
$access_deletereports	=	has_capability('block/ilp:deletereport', $context);
$access_viewreports		=	has_capability('block/ilp:viewreport', $context);	
$access_viewilp			=	has_capability('block/ilp:viewilp', $context);
$access_viewotherilp	=	has_capability('block/ilp:viewotherilp', $context);

//TODO: we will should not be in the course context change to another context
$PAGE->set_context($context);

?>










