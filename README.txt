SUNRISE HOTEL ROOM BOOKING AND BILLING SYSTEM
================================================

FILES
- index.html           Home page
- booking.html         Booking form and live estimated bill
- style.css            Burgundy, navy blue, white and gold styling
- script.js            JavaScript validation, dynamic behavior and bill calculation
- process_booking.php  Server-side validation and independent bill calculation

RUNNING THE PROJECT WITH XAMPP
1. Install and open XAMPP.
2. Start Apache from the XAMPP Control Panel.
3. Copy this project folder into: C:\xampp\htdocs\
4. Open a browser and visit:
   http://localhost/AdmissionNumber_HotelRoomBooking_Project/index.html
5. Select Book a Room, fill the form and submit it.
6. PHP must be run through localhost; opening the HTML file directly will not process PHP.

BILLING RULES
- Single room: KSh 3,500 per night (capacity 1)
- Double room: KSh 5,000 per night (capacity 2)
- Family room: KSh 7,500 per night (capacity 4)
- Breakfast: KSh 700 per guest per night
- Airport transfer: KSh 2,000 once
- Conference room: KSh 5,000 once (admission number 23238 ends in 8)

INDIVIDUALIZATION NOTE
Admission number 23238 ends in digit 8, so the assigned additional requirement is
the conference room booking option costing KSh 5,000. It is implemented in both
JavaScript and PHP, and is included in the final amount calculation.

TESTING IDEAS
1. Submit with empty fields; the browser should show validation errors.
2. Enter a phone number with fewer or more than 10 digits.
3. Select Single Room and try to enter 2 guests; the capacity warning should appear.
4. Check Breakfast and change the number of guests or nights; the estimate should update.
5. Try each extra option and check the estimate.
6. Submit a valid booking and verify the PHP confirmation and total.
7. Test direct POST tampering if demonstrating that PHP recalculates the amount.

SAMPLE CALCULATION
Double room, 3 nights, 2 guests, breakfast and airport transfer:
Room = 5,000 x 3 = KSh 15,000
Breakfast = 700 x 2 x 3 = KSh 4,200
Transfer = KSh 2,000
Total = KSh 21,200

PRACTICAL DEMONSTRATION SNAPSHOTS
Capture your own browser screenshots after running with XAMPP:
- Home page: index.html
- Booking form and estimate: booking.html
- Capacity warning after choosing Single Room and 2 guests
- Updated bill after selecting breakfast and airport transfer
- Booking confirmation page after submitting valid details
- Conference room option for admission-number ending 8
