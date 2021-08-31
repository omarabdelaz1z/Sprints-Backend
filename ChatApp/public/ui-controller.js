import { bind } from "./pusher.js";
import {
  isContentEmpty,
  isNewLine,
  isSend,
  formatTime,
  reset,
  getUserCount,
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

const handleMessageBox = async (event) => {
  if (event.shiftKey || event.altKey || event.ctrlKey || event.key === "Enter")
    event.preventDefault();

  const content = messageComponent.value;

  if (isContentEmpty(content)) {
    // console.log("Empty messages are not allowed.");
    return;
  }

  if (event.type === "click" || isSend(event)) {
    // console.log(`Message sent: ${content}`);

    const data = JSON.parse(localStorage.getItem("data"));
    await fetch("/message", {
      body: JSON.stringify({
        ...data,
        content,
      }),
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      method: "POST",
    });

    const res = await getUserCount(data.group);
    // console.log(res);
    const groupCount = res?.count || 0;

    onlineUsers.textContent = `${groupCount} users online now`;

    messageComponent.value = "";
    timer = reset(minutes); // reset timer.
    return;
  }

  if (isNewLine(event)) {
    // console.log("Alt + Enter Pressed");
    messageComponent.value += "\r\n";
  }
};

const joinGroupHandler = async () => {
  const username = usernameInput.value;
  const group = groupInput.value;

  if (isContentEmpty(group) || isContentEmpty(username)) return;

  const res = await fetch("/login", {
    body: JSON.stringify({
      username,
      group,
    }),
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    method: "POST",
  });

  const { userId } = await res.json();

  localStorage.setItem(
    "data",
    JSON.stringify({
      username,
      userId,
      group,
    })
  );

  pageTitle.innerHTML = `Chat with Group ${group}`;
  groupHeader.innerHTML = `Group Name: ${group}`;

  screenOne.style.display = "none";
  screenTwo.style.display = "block";

  bind(group); // register group with the service.

  setInterval(countDown, 1000);
};

const exitChat = async () => {
  // console.log(localStorage.getItem("data"));
  const { userId, group } = JSON.parse(localStorage.getItem("data"));
  try {
    await fetch("/logout", {
      body: JSON.stringify({
        userId,
        group,
      }),
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      method: "POST",
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

  if (timer < 0) {
    // console.log("It will reset the timer for now.");
    // timer = reset(minutes);
    exitChat();
  }
};

sendMessageButton.addEventListener("click", handleMessageBox);
messageComponent.addEventListener("keydown", handleMessageBox);
joinGroupButton.addEventListener("click", joinGroupHandler);
logoutButton.addEventListener("click", exitChat);
