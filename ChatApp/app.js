require("dotenv").config();
const path = require("path");
const express = require("express");
const Pusher = require("pusher");
const PORT = process.env.PORT || 3000;

const pusher = new Pusher({
  appId: process.env.APP_ID,
  key: process.env.KEY,
  secret: process.env.SECRET,
  cluster: process.env.CLUSTER,
  useTLS: true,
});

const channels = {};
const generateID = () => Math.random().toString(36).substr(2, 9);

const app = express();

app.use(express.json());
app.use(express.static(path.join(__dirname, "public")));

app.post("/message", async (req, res) => {
  const { group } = req.body;

  // console.log(channels);
  // Trigger a specific a channel that a message with sent to a specifc group.
  const receivedDate = new Date().toLocaleString(undefined, {
    timeStyle: "short",
    dateStyle: "medium",
  });

  const triggered = await pusher.trigger(group, "message", {
    ...req.body,
    receivedDate,
  });

  // Once a message sent, return subscription count as response.
  res.send();
});

app.post("/login", async (req, res) => {
  const { group, username } = req.body;
  const userId = generateID();

  const user = { userId, username, group };

  // Check if the channel exists:
  // Add the group and assign the user to list of users of that group.
  // Otherwise, the channel exists and the user is added to the list.

  if (!channels[group]) {
    channels[group] = [user];
  } else {
    channels[group].push(user);
  }
  res.send(user);
});

app.post("/logout", async (req, res) => {
  const { userId, group } = req.body;
  channels[group] = channels[group].filter((user) => user.userId !== userId);
  // console.log(channels);
  res.status(200).end();
});

app.get("/usercount/:group/", (req, res) => {
  const { group } = req.params;
  const channel = channels?.[group];
  if (!channel) res.status(404).json({ message: "Not Found" });
  else res.status(200).json({ group, count: channel.length });
});

app.listen(PORT, () => {
  console.log(`Example app listening at http://localhost:${PORT}`);
});
