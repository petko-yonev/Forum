

function ShowSingInForm(){
    document.getElementById("Register").style.display = "none";
    document.getElementById("logIn").style.display = "block";
}
function ShowRegisterForm(){
    document.getElementById("Register").style.display = "block";
    document.getElementById("logIn").style.display = "none";
}


function ShowAccountInfo(){
    window.location.href='accountSettings.php?page=1&show=info';
}
function ShowYourThreads(){
    window.location.href='accountSettings.php?page=1&show=threads';
}
function ShowUserData(){
    window.location.href='accountSettings.php?page=1&show=data';
}


function previewPic(){
    document.getElementsByClassName("settings_pic")[0].style.display = "none";
    document.getElementsByClassName("preview_new_pic")[0].style.display = "block";
    document.getElementsByClassName("Change_Profile_pic")[0].style.display = "inline";
    var file = document.getElementById("file").files;
    if(file.length > 0){
        var fileReader = new FileReader();
        fileReader.onload = function (event){
            document.getElementById("preview_new_pic").setAttribute("src", event.target.result)
        }
        fileReader.readAsDataURL(file[0]);
    }
}

function ShowPass(){
    var i = document.getElementsByClassName("PsswordVisibility");
    var b = document.getElementById("ShowPassBtn");
    for(var x = 0; x < i.length ;x++){
        if(i[x].type === "password"){ 
            i[x].type = "text";
            b.value = "Скрии Паролите";
        } else {
            i[x].type = "password";
            b.value = "Покажи Паролите";
        }
    }
}

function checkbox_checked(){
    var button = document.getElementsByClassName("SendThreadButton")[0];
    var checkbox = document.getElementsByClassName("checkbox_checked");

    for(var x = 0; x < checkbox.length; x++){
        if(checkbox[x].checked){
            button.style.display = "block";
            break;
        } else {
            button.style.display = "none";
        }
    }
}
//Function to change page by input in selected Threads
function changePageThreads(){
    var pageInput = document.getElementsByClassName("pageInput")[0];
    var category = document.getElementById("pageCategory").value;
    var lastPage = document.getElementById("lastPage").value;
    var page = document.getElementsByClassName("pageInput")[0].value;
    pageInput.addEventListener("keyup", function(event){
        if(event.keyCode === 13){
            if(Number(page) <= Number(lastPage)){
                window.location.href='threads.php?page='+page+'&category='+category;
            } else if(Number(lastPage) == 0){
                window.location.href='threads.php?page=1&category='+category;
            }else {
                window.location.href='threads.php?page='+lastPage+'&category='+category;
            }
        }
    })
}
//Function to change page by input in users Threads
function changePageUserThreads(){
    var pageInput = document.getElementsByClassName("pageInput")[0];
    var lastPage = document.getElementById("lastPage").value;
    var page = document.getElementsByClassName("pageInput")[0].value;
    pageInput.addEventListener("keyup", function(event){
        if(event.keyCode === 13){
            if(Number(page) <= Number(lastPage)){
                window.location.href='accountSettings.php?page='+page+'&show=threads';
            } else if(Number(lastPage) == 0){
                window.location.href='accountSettings.php?page=1&show=threads';
            }else {
                window.location.href='accountSettings.php?page='+lastPage+'&show=threads';
            }
        }
    })
}

