$(document).ready(function(){
    //code goes here










     /*
    * 
    * LOGIN/SIGN UP TITLE CHANGE
    * 
    * 
    */
   $("#signupCard").css("background-color", "lightgrey");

    $("#signupCard").on("click", function(){
        $("#signupCard").css("background-color", "");
        $("#loginCard").css("background-color", "lightgrey");
        $(".login_open").hide();
        $(".sign_up_title").fadeIn(800);
        $(".login_title").hide();
        $(".sign_up_open").fadeIn(800);
        
    });
    $("#loginCard").on("click", function(){
        $("#loginCard").css("background-color", "");
        $("#signupCard").css("background-color", "lightgrey");
        $(".sign_up_open").hide();
        $(".login_title").fadeIn(800);
        $(".sign_up_title").hide();
        $(".login_open").fadeIn(800);
        
        
        
        
    });





     /*
    * 
    * LOVE BUTTON
    * 
    * 
    */
   $("#projectFeed").on("click", ".love-btn", function(){
    console.log($(this));
        // Store the components in variables
        var love_btn = $(this);
        var love_icon = love_btn.find(".love-icon");
        var love_count = love_btn.find(".love-count");

        // Values that we are going to send via ajax post
        var project_id = love_btn.data("project");

        $.post(
            "/loves/add.php", 
            {
                "project_id": project_id
            },
            function(love_results){
                console.log(love_results);
                love_results = JSON.parse(love_results); // jason.parse takes jason string and turns it into an object so if you have a bunch of keys {keyone: 1 keytwo:2}, you can then call on a specific key and get the answer,  calling keyone will give you 1.

                // the data in the network preview can be called like the .error here.
                if(love_results.error == false){ // if loved worked..
                    if(love_results.loved == "loved"){
                        love_icon.removeClass("far").addClass("fas");
                        love_count.html(love_results.love_count);

                    } else if(love_results.loved == "unloved"){
                        love_icon.removeClass("fas").addClass("far");
                        love_count.html(love_results.love_count);
                    }
                }
            }
        );
   });

   /*
    * 
    * Submit comment Button
    * 
    * 
    */
    $("#projectFeed").on("submit", ".comment-form", function(e) {
        e.preventDefault();

        //store comment components
        var comment_form = $(this);
        var comment_box = comment_form.find(".comment-box");
        var comment_count = comment_form.closest(".project-post").find(".comment-count");
        var comment_loop = comment_form.closest(".project-post").find(".comment-loop");

        //store the values
        var project_id = comment_form.data("project"); // data looks for anything that says data ex; data-project on our comment form on index page
        var comment_text = comment_box.val();

        console.log(project_id, comment_text);

        if( $.trim( comment_text ).length > 0 ){ // If you typed something. trim removes spaces before/after

            $.post(
                "/comments/add.php", // url
                {
                    project_id: project_id,
                    comment: comment_text
                },//data
                function(comment_data) { // complete function
                    //do stuff here...
                    comment_data = JSON.parse(comment_data);
                    console.log(comment_data);

                    if(comment_data.error == false ){
                        comment_count.html(comment_data.comment_count);
                        var comment_html = "";

                        $.each(comment_data.comments, function(index, comment){
                            comment_html += "<div id='comment-"+comment.id+"' class='user-comment ";
                            comment_html += (comment.user_owns == 'true') ? 'my_comment' : '';
                            comment_html += "'><p>";
                            comment_html += "<span class='font-weight-bold comment-username'>"+comment.username+":</span>";
                            comment_html += comment.comment;
                            comment_html += (comment.user_owns == 'true') ? "<a class='trash-btn' data-target='"+comment.id+"' ><i class='text-danger fas fa-trash'></i></a>" : '';
                            comment_html += "</p></div>";
                        });

                        comment_loop.html(comment_html);
                        comment_loop.slideDown(500, function(){
                            $(this).animate( {
                                scrollTop: $(this).prop("scrollHeight")
                            }, 500);
                        });
                        comment_box.val(""); // wipe comment box value after submit
                    }
                }
            );

        }
    });

    /*
    *
    * DELETE PROJECT NO REFRESH 
    * 
    */

    $("#projectFeed").on("click", ".deleteProjectButton", function(e){
        e.preventDefault();
        
        var this_project = $(this).attr("data-project");
        var project_id = $("#project-"+this_project);
        
        console.log(this_project);
        $.ajax({
            url: "/projects/delete.php",
            data: {
                id: this_project
            },
            success: function(result){
                console.log(result);
                if(result){
                    project_id.fadeOut("3000", function(){
                        project_id.remove();
                    });
                }



            }
        });
    });


     /*
    * 
    * COMMENT BUTTON
    * 
    * 
    */
   $("#projectFeed").on("click", ".comment-btn", function(){
    var comment_loop = $(this).closest(".project-post").find(".comment-loop");

    comment_loop.slideToggle(500, function(){
        $(this).animate( {
            scrollTop: $(this).prop("scrollHeight")
        }, 500);
    });
});




    /*
    * 
    * 
    * FILE UPLOADING
    * 
    */
    $("#file-with-preview").on("change", function(){
        previewFile();
    });

function previewFile() {
    //Select our preview <img>
    //Get the file contents from upload field
    //Set the src of our <img> to the uploaded file location

    var preview = $("#img-preview");
    // [0] selects the first fast of the array. each of these had an array if viewed in the console.
    var file = $("#file-with-preview")[0].files[0];

    var reader = new FileReader;

    // Run when file finishes reading
    reader.onloadend = function() {
        //change img src to the reader result to post img
        preview.attr("src", reader.result);
    }
    //if file exists
    if(file){
        //call upon reader to readasdataurl
        reader.readAsDataURL(file);
    } else {
        //change img src to nothing to erase when pressing cancel on img upload
        preview.attr("src", "")
    }

    console.log(preview, file);



}
        
    /*
    *
    * LOAD PHP PAGE TO MODAL POPUP ON INDEX PAGE.  
    * 
    */

    $(".modal-button").on("click", function(){
    
    
       var project_id = $(this).attr("data-project");
        $.ajax({ 

            url: "/projects/show.php", 
            data: {
                id: project_id
            },
            success: function(modal_result){
                $(".modal-dialog").html(modal_result);
            }
        });
        
    });


   

    /*
    *
    * SEARCH BAR  
    * SEARCH BAR  
    * SEARCH BAR  
    * 
    */
    $("#search_form").on("submit", function(e){
        e.preventDefault();
    });

    $("input#search").on("keyup", function(e){
        var user_search = $(this).val();
        if(user_search.length > 2){
            // console.log(user_search);

            $.ajax({
                url: "/search_results.php",
                type: "get",
                data: {
                    search: user_search
                },
                success: function(search_results){
                    search_results = JSON.parse(search_results);

                    var output = "<div class='list-group'>";
                    $.each(search_results, function(i, search_result){
                        if(search_result.user_id){

                            output += "<a class='list-group-item list-group-item-action d-flex align-items-center' href='/projects?id="+search_result.id+"'><div>"+search_result.title+"</div></a>";
                            
                        }else {

                            output += "<a class='list-group-item list-group-item-action d-flex align-items-center' href='/users?id="+search_result.id+"'><div id='dropdownpicholder'><img id='dropdownpic' src='"+search_result.profile_pic+"'></div><div>"+search_result.title+"</div></a>";
                        }
                    });
                    output += "</div>";

                    $("#search_results").html(output);
                    console.log(search_results);

                }
            });
        } else {
            $("#search_results").html("");
        }
    });
   
    

    $("#projectFeed").on("click", ".trash-btn", function(e){
        e.preventDefault();

        var this_comment = $(this).attr("data-target");
        var comment_box = $(this_comment).find(".comment-box");
        var comment_id = $("#comment-"+this_comment);

        $.ajax({
            url: "/comments/delete.php",
            data: {
                id: this_comment
            },
            success: function (results){
                comment_id.fadeOut();
            }
        });
        comment_box.remove();
        
    });


    
});