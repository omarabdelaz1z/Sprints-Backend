import { renderMessage } from "./utils.js";

// APP Data 
const APP_KEY = "48289bf44e93f3788eaa";
const CLUSTER = "eu";

const pusher = new Pusher(APP_KEY, { cluster: CLUSTER });

export const bind = (group) => {
  try{
    const channel = pusher.subscribe(group);
    channel.bind("message", (data) => renderMessage(data));
  }catch{
    console.log(error);
  }
};
