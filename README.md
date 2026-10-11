SUNRISE HOTEL ROOM BOOKING AND BILLING SYSTEM

FILES
index.html — hotel home page
booking.html — booking form, dynamic behaviour and live estimate
style.css — white, burgundy, gold and navy-blue design
script.js — JavaScript validation, room capacity checks and bill calculator
process_booking.php — server-side validation and independent bill calculation

RUN WITH XAMPP
1. Install and open XAMPP.
2. Start Apache in the XAMPP Control Panel.
3. Copy this whole folder into C:\xampp\htdocs\
4. Visit http://localhost/23238_HotelRoomBooking_Project/index.html
5. Open Book a Room, complete the form and submit it.
PHP will not run if you open the HTML page directly as a file.

PRICES
Single Room: KSh 3,500 per night, maximum 1 guest
Double Room: KSh 5,000 per night, maximum 2 guests
Family Room: KSh 7,500 per night, maximum 4 guests
Breakfast: KSh 700 per guest per day
Airport transfer: KSh 2,000 one time
Late checkout: KSh 2,000 one time

BILL FORMULA
Room rate × nights + breakfast (if selected: 700 × guests × nights)
+ airport transfer (if selected: 2,000) + late checkout (if selected: 2,000).

FEATURES
- JavaScript checks name, ID/passport, email, 10-digit phone, check-in date, nights, room and guests.
- Nights must be from 1 to 14 and guest count must fit room capacity.
- Transfer details appear only when transfer is selected.
- Estimated bill updates as options change.
- PHP validates submitted values again and independently calculates the total.
- User-submitted text is escaped before it is printed in the confirmation.

PRACTICAL DEMONSTRATION
- Change a room rate in both script.js and process_booking.php, then show the new estimate and confirmed total.
- Change a room capacity in both files and demonstrate the warning/server-side rejection.
- Select late checkout and show KSh 2,000 added to the total.
- Enter an invalid phone number or exceed room capacity to demonstrate validation.

This is a classroom prototype and does not save bookings to a database or send emails.
