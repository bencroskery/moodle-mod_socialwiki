
// Let page load
$(document).ready(function()
{
    // Click trigger for image
    $("#unlikelink").click(function()
    {
        // get url [, no data], success
        $.get("likeaj.php?pageid="+pageid , function(data)
        {
            $("#unlikelink").hide();
	    $("#likelink").show();
	    $("#numlikes").text(data); //data is the new number of likes
	    
        });
    });
    $("#likelink").click(function()
    {
        // get url [, no data], success
        $.get("likeaj.php?pageid="+pageid , function(data)
        {
            $("#likelink").hide();
	    $("#unlikelink").show();
	    $("#numlikes").text(data); //data is the new number of likes
        });
    });
});
