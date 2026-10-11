// Sunrise Hotel booking form: simple client-side validation and bill calculator.
// Admission number 23238 ends in 8: conference room hire costs KSh 5,000.
const roomRates = { single: 3500, double: 5000, family: 7500 };
const roomCapacities = { single: 1, double: 2, family: 4 };

const form = document.getElementById("bookingForm");
const roomType = document.getElementById("room_type");
const nightsInput = document.getElementById("nights");
const guestsInput = document.getElementById("guests");
const breakfastInput = document.getElementById("breakfast");
const transferInput = document.getElementById("airport_transfer");
const conferenceInput = document.getElementById("conference_room");

function money(amount) {
    return "KSh " + amount.toLocaleString("en-KE");
}

function checkRoomCapacity() {
    const type = roomType.value;
    const help = document.getElementById("capacityHelp");
    const warning = document.getElementById("capacityWarning");

    if (!type) {
        help.textContent = "Select a room to see its guest capacity.";
        warning.textContent = "";
        return false;
    }

    const capacity = roomCapacities[type];
    guestsInput.max = capacity;
    help.textContent = "Maximum guests for this room: " + capacity;
    if (Number(guestsInput.value) > capacity) {
        warning.textContent = "This room allows a maximum of " + capacity + " guest(s). Please change the room or guest count.";
        return false;
    }
    warning.textContent = "";
    return true;
}

function calculateBill() {
    const type = roomType.value;
    const nights = Number(nightsInput.value) || 0;
    const guests = Number(guestsInput.value) || 0;
    const roomCost = (roomRates[type] || 0) * nights;
    const breakfastCost = breakfastInput.checked ? 700 * guests * nights : 0;
    const transferCost = transferInput.checked ? 2000 : 0;
    const conferenceCost = conferenceInput.checked ? 5000 : 0;
    const total = roomCost + breakfastCost + transferCost + conferenceCost;

    document.getElementById("summaryRoom").textContent = type ? type.charAt(0).toUpperCase() + type.slice(1) + " Room" : "Not selected";
    document.getElementById("summaryStay").textContent = guests + (guests === 1 ? " guest · " : " guests · ") + nights + (nights === 1 ? " night" : " nights");
    document.getElementById("roomCost").textContent = money(roomCost);
    document.getElementById("breakfastCost").textContent = money(breakfastCost);
    document.getElementById("transferCost").textContent = money(transferCost);
    document.getElementById("conferenceCost").textContent = money(conferenceCost);
    document.getElementById("grandTotal").textContent = money(total);
}

function showError(id, message) {
    document.getElementById(id).textContent = message;
}

function validateBooking() {
    let valid = true;
    const name = document.getElementById("full_name").value.trim();
    const identity = document.getElementById("identity").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const checkIn = document.getElementById("check_in").value;
    const nights = Number(nightsInput.value);
    const guests = Number(guestsInput.value);
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phonePattern = /^\d{10}$/;

    showError("nameError", name ? "" : "Please enter your full name.");
    showError("identityError", identity ? "" : "Please enter your ID or passport number.");
    showError("emailError", emailPattern.test(email) ? "" : "Enter a valid email address.");
    showError("phoneError", phonePattern.test(phone) ? "" : "Phone number must contain exactly 10 digits.");
    showError("dateError", checkIn ? "" : "Please choose a check-in date.");
    showError("nightsError", Number.isInteger(nights) && nights >= 1 && nights <= 14 ? "" : "Choose between 1 and 14 nights.");
    showError("roomError", roomType.value ? "" : "Please select a room type.");
    showError("guestsError", Number.isInteger(guests) && guests > 0 ? "" : "Enter at least one guest.");

    if (!name || !identity || !emailPattern.test(email) || !phonePattern.test(phone) || !checkIn ||
        !(Number.isInteger(nights) && nights >= 1 && nights <= 14) || !roomType.value ||
        !(Number.isInteger(guests) && guests > 0) || !checkRoomCapacity()) {
        valid = false;
    }

    if (checkIn) {
        const selectedDate = new Date(checkIn + "T00:00:00");
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
            showError("dateError", "Check-in date cannot be in the past.");
            valid = false;
        }
    }

    document.getElementById("formStatus").textContent = valid ? "" : "Please correct the highlighted fields before submitting.";
    return valid;
}

// Dynamic transfer details: shown only when transfer is selected.
transferInput.addEventListener("change", function () {
    document.getElementById("transferDetails").hidden = !transferInput.checked;
    calculateBill();
});

// Update capacity and total when room type changes.
roomType.addEventListener("change", function () {
    checkRoomCapacity();
    calculateBill();
});

// Recalculate when relevant inputs change; these are examples of change/input events.
[nightsInput, guestsInput, breakfastInput, conferenceInput].forEach(function (item) {
    item.addEventListener("input", function () {
        checkRoomCapacity();
        calculateBill();
    });
    item.addEventListener("change", function () {
        checkRoomCapacity();
        calculateBill();
    });
});

form.addEventListener("submit", function (event) {
    if (!validateBooking()) {
        event.preventDefault();
    }
});

// Set the earliest check-in date to today and show the initial estimate.
const dateInput = document.getElementById("check_in");
const todayString = new Date();
const localToday = new Date(todayString.getTime() - todayString.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
dateInput.min = localToday;
calculateBill();
