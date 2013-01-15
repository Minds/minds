
// Function shows the documentation of a phpcrawl-class-method
// in a popup-window.
function show_documentation (method, path_to_documentation) {
  
  f = window.open(classref_page+method+".htm", "documentation", "width=720, height=500, scrollbars=yes");
  f.focus();

}

// Function for confirming a setup-delete
function delete_selected_setup ()
{
  var element = document.getElementById("selected_setup_filename");
  var selected_value = element.options[element.selectedIndex].value;
  
  con = confirm ("Do you want to delete the saved setup '" + selected_value + "' ?");
  
  if (con==true)
  {
    setAction("delete_setup", "_self");
  }

}

// Function for confirming a setup-save
function save_setupfile()
{
  var save_filename = document.getElementById("save_setup_filename").value;

  // Check if a setup with this name already exists
  var element = document.getElementById("selected_setup_filename");
  
  for (i=0; i<element.options.length; i++)
  {
    if (element.options[i].value == save_filename)
    {
      con = confirm("Do you want to overwrite the setup-file '" + save_filename + "'?");
      if (con == true)
      {
        setAction("save_setup", "_self");
      }
      return;
    }
  }
  
  setAction("save_setup", "_self");
}

// function sets the "action" (hidden field) and the target of the html-form and submits it
function setAction(ac, target) {

  document.options.action.value=ac;
  document.options.target=target;
  document.options.submit();
}

// function shows/hides the comment-div (and shows/hides the underlying select-boxes, IE-prob)
function showCommentDiv(div_id, flag)
{
  if (flag == true)
  {
    document.getElementById(div_id).style.visibility = "visible";
    
    document.getElementById("setFollowRedirects").style.visibility = "hidden";
    document.getElementById("setFollowRedirectsTillContent").style.visibility = "hidden";
    document.getElementById("setCookieHandling").style.visibility = "hidden";
    document.getElementById("setAggressiveLinkExtraction").style.visibility = "hidden";
    document.getElementById("obeyRobotsTxt").style.visibility = "hidden";
  }
  else
  {
    document.getElementById(div_id).style.visibility = "hidden";
    
    document.getElementById("setFollowRedirects").style.visibility = "visible";
    document.getElementById("setFollowRedirectsTillContent").style.visibility = "visible";
    document.getElementById("setCookieHandling").style.visibility = "visible";
    document.getElementById("setAggressiveLinkExtraction").style.visibility = "visible";
    document.getElementById("obeyRobotsTxt").style.visibility = "visible";
  }
}
