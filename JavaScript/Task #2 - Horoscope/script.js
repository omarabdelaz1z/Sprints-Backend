// Source: https://en.wikipedia.org/wiki/Astrological_sign
const ZODIAC_SIGNS = [
  {
    name: "Aries ",
    interval: { start: { month: 3, day: 21 }, end: { month: 4, day: 19 } },
  },
  {
    name: "Taurus",
    interval: { start: { month: 4, day: 20 }, end: { month: 5, day: 20 } },
  },
  {
    name: "Gemini",
    interval: { start: { month: 5, day: 21 }, end: { month: 6, day: 21 } },
  },
  {
    name: "Cancer",
    interval: { start: { month: 6, day: 22 }, end: { month: 7, day: 22 } },
  },
  {
    name: "Leo",
    interval: { start: { month: 7, day: 23 }, end: { month: 8, day: 22 } },
  },
  {
    name: "Virgo",
    interval: { start: { month: 8, day: 23 }, end: { month: 9, day: 22 } },
  },
  {
    name: "Libra",
    interval: { start: { month: 9, day: 23 }, end: { month: 10, day: 22 } },
  },
  {
    name: "Scorpio",
    interval: { start: { month: 10, day: 23 }, end: { month: 11, day: 22 } },
  },
  {
    name: "Sagittarius",
    interval: { start: { month: 11, day: 23 }, end: { month: 12, day: 21 } },
  },
  {
    name: "Capricorn",
    interval: { start: { month: 12, day: 22 }, end: { month: 1, day: 19 } },
  },
  {
    name: "Aquarius",
    interval: { start: { month: 1, day: 20 }, end: { month: 2, day: 18 } },
  },
  {
    name: "Pisces",
    interval: { start: { month: 2, day: 19 }, end: { month: 3, day: 20 } },
  },
];

const DAYS_IN_MONTH = {
  1: 31,
  2: 28,
  3: 31,
  4: 30,
  5: 31,
  6: 30,
  7: 31,
  8: 31,
  9: 30,
  10: 31,
  11: 30,
  12: 31,
};

/**
 * Find a zodiac sign that whose start <= (month, day) and end >= (month, day)
 * @param month
 * @param day
 * @returns zodiac object
 */
const getZodiacSign = (month, day) => {
  const currentTime = new Date(0, month - 1, day).getTime();

  for (const zodiac of ZODIAC_SIGNS) {
    const { start, end } = zodiac.interval;

    const startTime = new Date(0, start.month - 1, start.day).getTime();
    const endTime = new Date(0, end.month - 1, end.day).getTime();

    if (currentTime >= startTime && currentTime <= endTime) return zodiac;
  }
};

/**
 * Check if month is between [1, 12]
 * @param month
 * @returns Boolean
 */
const isMonthValid = (month) => {
  return month >= 1 && month <= 12;
};

/**
 * Check if the a day conform to DAYS_IN_MONTH object according to month.
 * @param month
 * @param day
 * @returns Boolean
 */
const isDayInMonth = (month, day) => {
  return day <= DAYS_IN_MONTH[month];
};

/**
 * Check if the name's length is >= 3 and have only characters.
 * @param name
 * @returns Boolean
 */
const isValidName = (name) =>
  name.length >= 3 && /^[a-zA-Z ]+$/.test(name.trim());

// I used IIFE to start the script once the page is open.
(function horoscope() {
  let name = prompt("Enter name?");
  while (!isValidName(name)) {
    name = prompt("Name must not be empty. Please Try Again");
  }

  let exceptionCounter = 0;
  let password = prompt("Enter password?");

  while (password !== "123") {
    exceptionCounter++;
    if (exceptionCounter > 2) {
      alert("Program Ends.");
      return;
    }
    password = prompt("Password is invalid. Please Try Again");
  }

  let month = prompt("Enter month number?");
  while (!isMonthValid(month)) {
    month = prompt("Please enter a valid month number?");
  }

  let day = prompt("Enter day number?");
  while (!isDayInMonth(month, day)) {
    day = prompt("Please enter a valid day number?");
  }

  const zodiac = getZodiacSign(month, day);
  alert(`Your zodiac sign is: ${zodiac.name}`);
})();
