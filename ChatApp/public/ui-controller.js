import { bind } from "./pusher.js";
import {
  isContentEmpty,
  isNewLine,
  isSend,
  formatTime,
  reset,
  getOnlineUsersCount,
} from "./utils.js";

const minutes = 1;
let timer = minutes * 60;

const pageTitle = document.getElementsByTagName("title")[0];
const screenOne = document.getElementById("screen-one");
const screenTwo = document.getElementById("screen-two");

const groupInput = document.getElementById("group-name");
const usernameInput = document.getElementById("username");
const joinGroupButton = document.getElementById("join-button");

const groupHeader = document.getElementById("group-header");
const onlineUsers = document.getElementById("online-users");
const logoutButton = document.getElementById("logout");
const sendMessageButton = document.getElementById("send-btn");
const messageComponent = document.getElementById("message-area");
const timerComponent = document.getElementById("countdown-timer");

const handleMessageBox = async (event) => 
{
  if (event.altKey || event.ctrlKey || event.key === "Enter")
    event.preventDefault();

  const content = messageComponent.value;

  // Empty messages are not allowed.
  if (isContentEmpty(content)) return;

  if (event.type === "click" || isSend(event)) 
  {
    console.log(content)
    const data = JSON.parse(localStorage.getItem("data"));
    const body = JSON.stringify({...data, content});

    console.log(body);

    try{
      await fetch("/message", {
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
        method: "POST",
        body
      }); 
    }

    catch(error){
      console.error(error);
    }finally{
      messageComponent.value = "";
      timer = reset(minutes); // reset timer.
    }
  }

  else if (isNewLine(event)) {
    messageComponent.value += "\r\n";
  }
};

const updateOnlineUsersCount = async () => {
  const { group } = JSON.parse(localStorage.getItem("data"));
  let count = 0;

  try{
    const res = await getOnlineUsersCount(group);
    count = res?.count;
  }
  catch(error){
    console.log(error);
  }finally{
    onlineUsers.textContent = `${count} users online now`;
  }
}

const joinGroupHandler = async () => {
  const username = usernameInput.value;
  const group = groupInput.value;

  if (isContentEmpty(group) || isContentEmpty(username)) return;

  const userData = { username, group };
  const body = JSON.stringify(userData);

  try{
    const res = await fetch("/login", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body
    });

    const { userId } = await res.json();

    userData.userId = userId;
    localStorage.setItem("data", JSON.stringify(userData));

    bind(group); // register group with the service.

    pageTitle.innerHTML = `Chat with Group ${group}`;
    groupHeader.innerHTML = `Group Name: ${group}`;
    
    screenOne.style.display = "none";
    screenTwo.style.display = "block";
    
    setInterval(updateOnlineUsersCount, 5000);
    setInterval(countDown, 1000);

  }catch(error){
    console.error(error);
  }

};

const exitChat = async () => {
  // console.log(localStorage.getItem("data"));
  const { userId, group } = JSON.parse(localStorage.getItem("data"));
  const body = JSON.stringify({ userId, group });

  try {
    await fetch("/logout", {
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      method: "POST",
      body
    });

    localStorage.removeItem("data");

    window.location.reload();
  } catch (err) {
    console.error(err);
  }
};

const countDown = () => {
  const formattedTime = formatTime(timer);
  timerComponent.innerHTML = `You will be logout after (${formattedTime})`;
  timer--;

  if (timer < 0) exitChat();
};

sendMessageButton.addEventListener("click", handleMessageBox);
messageComponent.addEventListener("keydown", handleMessageBox);
joinGroupButton.addEventListener("click", joinGroupHandler);
logoutButton.addEventListener("click", exitChat);
