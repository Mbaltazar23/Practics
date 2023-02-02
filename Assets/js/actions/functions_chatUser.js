const searchBar = document.querySelector(".search input"),
        searchIcon = document.querySelector(".search button"),
        usersList = document.querySelector(".users-list");
		
$(document).ready(function(){
	refreshUserList();
});

searchIcon.onclick = () => {
    searchBar.classList.toggle("show");
    searchIcon.classList.toggle("active");
    searchBar.focus();
    if (searchBar.classList.contains("active")) {
        searchBar.value = "";
        searchBar.classList.remove("active");
    }
};

searchBar.onkeyup = () => {
    let searchTerm = searchBar.value;
    if (searchTerm != "") {
        searchBar.classList.add("active");
    } else {
        searchBar.classList.remove("active");
    }
    let xhr = new XMLHttpRequest();
    xhr.open("POST", urlIndex + "/Home/searchPersons", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                loadChats(data, document.querySelector("#idPerPass").value);
            }
        }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("searchTerm=" + searchTerm);
};


function abrirChat() {
    $("#btnSearch").trigger("click");
    $(".chat-area").hide();
    $(".users").show();
    searchBar.value = "";
    usersList.innerHTML = "";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = urlIndex + '/Home/getUser/' + id;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {

                let srcAvatar = objData.data.avatar != "" ? urlIndex + "/Assets/img/perfil/" + objData.data.avatar :
                        urlIndex + "/Assets/img/perfil/perfil-portada.jpg";

                let status = objData.data.statusLog != false ? "Activo" : "Inactivo";
                let htmlContent = `
          <img src="${srcAvatar}" alt="">
          <div class="details">
            <span>${objData.data.nombres + " " + objData.data.apellidos}</span>
            <p>${status}</p>
          </div>`;
                document.querySelector(".content").innerHTML = htmlContent;
                $("#modalFormChat").modal('show');
                cargarChats(objData.data);
            }
        }
    };
}

function cerrarVentanaChat() {
    $("#modalFormChat").modal('hide');
}


function cargarChats(Persona) {
    let srcAvatar = Persona.avatar != "" ? urlIndex + "/Assets/img/perfil/" + Persona.avatar :
            urlIndex + "/Assets/img/perfil/perfil-portada.jpg";

    let status = Persona.statusLog != false ? "Activo" : "Inactivo";
    let htmlContent = `
          <img src="${srcAvatar}" alt="">
          <div class="details">
            <span>${Persona.nombres + " " + Persona.apellidos}</span>
            <p>${status}</p>
          </div>`;
    document.querySelector(".users-list").innerHTML = htmlContent;

}
function refreshUserList() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", urlIndex + "/Home/searchPersonsActives", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                if (!searchBar.classList.contains("active")) {
                    loadChats(data, document.querySelector("#idPerPass").value);
                }
            }
        }
    };
    xhr.send();
    setTimeout(refreshUserList, 5000);
}

function loadChats(data, idpersona) {
    let objData = JSON.parse(data);
    //console.log(objData);
    let htmlContent = "", srcAvatar = "", status = "", tu = "", msg = "";
    if (objData != "") {
        //console.log(objData);
        for (let i = 0; i < objData.length; i++) {
            srcAvatar = objData[i].avatar != "" ?
                    urlIndex + "/Assets/img/perfil/" + objData[i].avatar :
                    urlIndex + "/Assets/img/perfil/perfil-portada.jpg";

            msg = objData[i].messages != "" ? objData[i].messages : "No hay mensajes disponibles";

            status = objData[i].sesion == "Inactivo" ? "offline" : "";

            tu = idpersona == objData[i].personaRecep ? "Tu: " : "";

            htmlContent += `<a onClick="selectUser(${objData[i].idpersona})">
                    <div class="content">
                    <img src="${srcAvatar}" alt="${objData[i].avatar}">
                    <div class="details">
                        <span>${objData[i].nombres + " " + objData[i].apellidos}</span>
                        <p>${tu + "" + msg}</p>
                    </div>
                    </div>
                    <div class="status-dot ${status}"><i class="fas fa-circle"></i></div>
                </a>`;
        }
        usersList.innerHTML = htmlContent;
    } else {
        usersList.innerHTML = "No hay personas disponibles para charlar";
    }
}

$(document).ready(function () {
    if (document.querySelector("#BtnSubmit")) {
        document.querySelector(".chat-box").innerHTML = "";
        let xhr = new XMLHttpRequest();
        xhr.open("POST", urlIndex + "/Home/getChats", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    loadChatsUser(data);
                    if (!chatBox.classList.contains("active")) {
                        scrollToBottom();
                    }
                }
            }
        };
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("incoming_id=" + document.querySelector(".incoming_id").value);
    }
});


function selectUser(idpersona) {
    $(".chat-area").show();
    $(".users").hide();
    document.querySelector(".content").innerHTML = "";
    document.querySelector(".users-list").innerHTML = "";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = urlIndex + '/Home/getUser/' + idpersona;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {

                let srcAvatar = objData.data.avatar != "" ? urlIndex + "/Assets/img/perfil/" + objData.data.avatar :
                        urlIndex + "/Assets/img/perfil/perfil-portada.jpg";

                let status = objData.data.sesion != "Inactivo" ? "Activo" : "Inactivo";
                let htmlContent = `
          <a onClick="volverAtras()" class="back-icon"><i class="fas fa-arrow-left"></i></a>
          <img src="${srcAvatar}" alt="">
          <div class="details">
            <span>${objData.data.nombres + " " + objData.data.apellidos}</span>
            <p>${status}</p>
          </div>`;
                document.querySelector(".incoming_id").value = objData.data.idpersona;
                document.querySelector("#bodyChat").innerHTML = htmlContent;
            }
        }
    };
}

function volverAtras() {
    $(".chat-area").hide();
    $(".users").show();
    searchBar.value = "";
    document.querySelector("#bodyChat").innerHTML = " ";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = urlIndex + '/Home/getUser/' + id;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {

                let srcAvatar = objData.data.avatar != "" ? urlIndex + "/Assets/img/perfil/" + objData.data.avatar :
                        urlIndex + "/Assets/img/perfil/perfil-portada.jpg";

                let status = objData.data.statusLog != false ? "Activo" : "Inactivo";
                let htmlContent = `
          <img src="${srcAvatar}" alt="">
          <div class="details">
            <span>${objData.data.nombres + " " + objData.data.apellidos}</span>
            <p>${status}</p>
          </div>`;
                document.querySelector(".content").innerHTML = htmlContent;
                cargarChatsReturn(id);
            }
        }
    };
}


function cargarChatsReturn(idpersona) {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", urlIndex + "/Home/searchPersonsActives", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                if (!searchBar.classList.contains("active")) {
                    let objData = JSON.parse(data);
                    //console.log(objData);
                    let htmlContent = "", srcAvatar = "", status = "", tu = "", msg = "";
                    if (objData != "") {
                        //console.log(objData);
                        for (let i = 0; i < objData.length; i++) {
                            srcAvatar = objData[i].avatar != "" ?
                                    urlIndex + "/Assets/img/perfil/" + objData[i].avatar :
                                    urlIndex + "/Assets/img/perfil/perfil-portada.jpg";

                            msg = objData[i].messages != "" ? objData[i].messages : "No hay mensajes disponibles";

                            status = objData[i].sesion == "Inactivo" ? "offline" : "";

                            tu = idpersona == objData[i].personaRecep ? "Tu: " : "";

                            htmlContent += `<a onClick="selectUser(${objData[i].idpersona})">
                    <div class="content">
                    <img src="${srcAvatar}" alt="${objData[i].avatar}">
                    <div class="details">
                        <span>${objData[i].nombres + " " + objData[i].apellidos}</span>
                        <p>${tu + "" + msg}</p>
                    </div>
                    </div>
                    <div class="status-dot ${status}"><i class="fas fa-circle"></i></div>
                </a>`;
                        }
                        usersList.innerHTML = htmlContent;
                    } else {
                        usersList.innerHTML = "No hay personas disponibles para charlar";
                    }
                }
            }
        }
    };
    xhr.send();
}


setInterval(() => {
    document.querySelector(".chat-box").innerHTML = "";
    let xhr = new XMLHttpRequest();
    xhr.open("POST", urlIndex + "/Home/getChats", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                loadChatsUser(data);
                if (!document.querySelector(".chat-box").classList.contains("active")) {
                    scrollToBottom();
                }
            }
        }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("incoming_id=" + document.querySelector(".incoming_id").value);
}, 500);

