const form = document.querySelector(".typing-area"),
  incoming_id = $(".incoming_id").val(),
  inputField = form.querySelector(".input-field"),
  sendBtn = document.querySelector("#BtnSubmit"),
  chatBox = document.querySelector(".chat-box");

form.onsubmit = (e) => {
  e.preventDefault();
};

inputField.focus();
inputField.onkeyup = () => {
  if (inputField.value != "") {
    sendBtn.classList.add("active");
  } else {
    sendBtn.classList.remove("active");
  }
};

sendBtn.onclick = () => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", urlIndex + "/Home/setChatPerson", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        inputField.value = "";
        scrollToBottom();
      }
    }
  };
  let formData = new FormData(form);
  xhr.send(formData);
};

chatBox.onmouseenter = () => {
  chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
  chatBox.classList.remove("active");
};

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}

function loadChatsUser(data) {
  let objData = JSON.parse(data);
  //console.log(objData);
  if (objData.length > 0) {
    let htmlContent = "",
      srcAvatar = "";
    for (let i = 0; i < objData.length; i++) {
      srcAvatar =
        objData[i].avatar != ""
          ? urlIndex + "/Assets/img/perfil/" + objData[i].avatar
          : urlIndex + "/Assets/img/perfil/perfil-portada.jpg";
      if (objData[i].personaid_rec != incoming_id) {
        htmlContent = `<div class="chat incoming">
                                <img src="${srcAvatar}" alt="${objData[i].avatar}">
                                <div class="details">
                                    <p>${objData[i].texto}</p>
                                </div>
                                </div>`;
      } else {
        htmlContent = `<div class="chat outgoing">
                                <div class="details">
                                    <p>${objData[i].texto}</p>
                                </div>
                                </div>`;
      }
      chatBox.innerHTML += htmlContent;
    }
  } else {
    chatBox.innerHTML = `<div class="text">No hay mensajes disponibles. Una vez que envíe el mensaje, aparecerán aquí.</div>`;
  }
}
