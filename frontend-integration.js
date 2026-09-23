// Add/replace these functions in your existing script.js.
// Point API_BASE at wherever you deploy the /api folder.
const API_BASE = '/api';

async function apiPost(path, data) {
    const res = await fetch(`${API_BASE}/${path}`, {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    });
    return res.json();
}

async function apiGet(path) {
    const res = await fetch(`${API_BASE}/${path}`, { credentials: 'include' });
    return res.json();
}

// Load hotels from the DB instead of hardcoded cards
async function loadHotels(category = 'all', location = '') {
    const params = new URLSearchParams({ category, location });
    const result = await apiGet(`hotels.php?${params}`);
    if (result.success) renderHotelGrid(result.hotels); // write renderHotelGrid to build cards from this array
}

// Wire up the search box
function searchHotels() {
    const location = document.getElementById('location').value;
    loadHotels('all', location);
}

// Wire up the booking modal's "Confirm Booking" button
async function confirmBooking() {
    const result = await apiPost('book.php', {
        hotel_id: window.selectedHotelId,       // set this when opening the modal
        guest_name: document.getElementById('guestName').value,
        guest_email: document.getElementById('guestEmail').value,
        checkin: document.getElementById('checkin').value,
        checkout: document.getElementById('checkout').value,
        guests: document.getElementById('guests').value,
    });
    showToast(result.message);
    if (result.success) closeModal('bookingModal');
}

// Wire up sign in
async function loginMessage() {
    const email = document.querySelector('#loginModal input[type=email]').value;
    const password = document.querySelector('#loginModal input[type=password]').value;
    const result = await apiPost('login.php', { email, password });
    showToast(result.message);
    if (result.success) closeModal('loginModal');
}

// Wire up the newsletter form
async function subscribe(event) {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const result = await apiPost('subscribe.php', { email });
    showToast(result.message);
}
