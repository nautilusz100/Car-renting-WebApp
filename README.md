## iKarRental – Project Description

This project is a PHP-based web application developed for an assignment where the goal was to implement the website for a fictional car rental service. The system allows visitors and registered users to browse available cars, make reservations, and manage their bookings. Administrators can manage cars and view or modify all reservations.

### Core Requirements

The application needed to support the following functionalities:

### Public Features
- Browsing all available cars on the main list page.
- Filtering cars by:
  - availability for a specific date range,
  - transmission type,
  - passenger capacity,
  - daily rental price range.
- Viewing a dedicated detail page for each car, including all specifications.

### User Authentication
- User registration with full validation.
- User login with proper error handling.
- Logged-in users can see their booking history on a profile page.
- Logout function accessible from every page.

### Car Reservation
- Logged-in users can reserve cars for a selected date interval.
- Successful reservation shows the booking details, the chosen car, and the total price.
- Failed reservation (overlap with an existing booking) displays an error and allows returning to the main page.

### Administrator Features
- A separate admin login (default credentials provided).
- Admins can:
  - add new cars,
  - edit car data,
  - delete cars,
  - view all reservations on the admin profile page,
  - delete reservations attached to a car when editing.

### Technical and Design Requirements
- No PHP frameworks or external backend libraries allowed.
- Mobile-friendly, aesthetically clean UI design.

![Homepage – guest view](https://github.com/user-attachments/assets/22aac1a2-3ed0-42e4-92a6-7a5d1eda177e)

*Homepage (guest view) – landing page accessible without authentication*

![Registration page](https://github.com/user-attachments/assets/8d72232b-0f69-49e5-839e-5e4149a7417a)

*Registration page – creating a new user account*

![Login page](https://github.com/user-attachments/assets/9bc27e1a-a2d1-472c-93c7-de17ecaf7979)

*Login page – authentication for existing users*

![User dashboard](https://github.com/user-attachments/assets/8969d89d-9b08-4704-9874-e9bc847d2f7e)

*User dashboard – homepage after standard user login*

![Admin dashboard](https://github.com/user-attachments/assets/a9f9dfb5-6aa1-444a-aaae-9a39bbe80a7e)

*Admin dashboard – homepage after administrator login*

