"use strict";
const tasks = [];

const state = {
  isEditing: false,
  index: -1,
};

const toggle = (button) => {
  button.style.display = button.style.display === "none" ? "inline" : "none";
};

const toggleButtons = (buttons) => {
  buttons.forEach((button) => {
    toggle(button);
  });
};

// Todo Actions: Functions
const addTask = () => {
  const name = document.querySelector("#task-name").value;
  document.querySelector("#task-name").value = "";
  const priority = document.querySelector("#task-priority").value;

  if (name !== "" && priority > 0) {
    const task = { name: name, priority: priority, isEdited: false };

    tasks.push(task);
    renderTable();
  }
};

const moveUp = (i) => {
  if (i > 0) {
    const oldTask = tasks[i];
    tasks[i] = tasks[i - 1];
    tasks[i - 1] = oldTask;

    renderTable();
  }
};

const moveDown = (i) => {
  if (i < tasks.length - 1) {
    const oldTask = tasks[i];
    tasks[i] = tasks[i + 1];
    tasks[i + 1] = oldTask;
    renderTable();
  }
};

const editTask = (i) => {
  const div = document.querySelector(".col-md-2");
  const buttons = div.children;
  toggleButtons([...buttons]);

  state.isEditing = true;
  state.index = i;

  const { name, priority } = tasks[i];
  document.querySelector("#task-name").value = name;
  document.querySelector("#task-priority").value = priority;

  renderTable();
};

const saveChanges = () => {
  const buttons = document.querySelector(".col-md-2").children;
  toggleButtons([...buttons]);

  const task = {
    name: document.querySelector("#task-name").value,
    priority: document.querySelector("#task-priority").value,
  };

  tasks[state.index] = task;

  state.isEditing = false;
  state.index = -1;

  renderTable();
};

const cancelChanges = () => {
  const buttons = document.querySelector(".col-md-2").children;
  toggleButtons([...buttons]);

  state.isEditing = false;
  state.index = -1;
  renderTable();
};

const deleteTask = (i) => {
  if (confirm("Are you sure ?")) {
    tasks.splice(i, 1);
    renderTable();
  }
};

const getPriorityName = function getPriorityName(priority) {
  switch (priority) {
    case "1":
      return "High";
    case "2":
      return "Medium";
    case "3":
      return "Low";
    default:
      throw console.error("priority is undefined");
  }
};

const renderTable = function () {
  const tbody = document.querySelector("#tasks-tbody");
  tbody.innerHTML = "";

  const disable = state.isEditing ? "disabled" : "''";

  tasks.forEach((task, index) => {
    const priority = getPriorityName(task.priority);

    const upButton =
      index > 0
        ? `<button class="btn btn-sm btn-secondary" onclick="moveUp(${index})" ${disable}>Up</button>`
        : "";

    const downButton =
      index < tasks.length - 1
        ? `<button class="btn btn-sm btn-secondary" onclick="moveDown(${index})" ${disable}>Down</button>`
        : ``;

    const actionButtons = `<button id='edit-task' class="btn btn-sm btn-primary" onclick="editTask(${index})" ${disable}> Edit </button>
                           <button id='delete-task' class="btn btn-sm btn-danger" onclick="deleteTask(${index})" ${disable}> Delete </button>`;

    const row = `<tr>
      <td>${index + 1}</td>
      <td>${task.name}</td>
      <td>${priority}</td>
      <td>
        ${upButton}
        ${downButton}
      </td>
      <td> 
        ${actionButtons}
      </td>
    </tr>`;

    tbody.insertAdjacentHTML("beforeEnd", row);
  });
};

const addTaskButton = document.getElementById("add-task");
addTaskButton.addEventListener("click", addTask);
