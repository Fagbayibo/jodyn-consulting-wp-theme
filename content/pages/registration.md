# Registration

URL: /register/
Client's original: [source/calendar-register-insights-contact-client-original.md](../source/calendar-register-insights-contact-client-original.md) (Page 2)

**Balance:** a training page, so it is all training. The client's words are kept as written wherever they work. Every change from the original is marked `> Changed:`. Gaps the client must fill are marked `[PLACEHOLDER]`.

**Three sections on the page** (hero, how it works, the form), then the confirmation that replaces the form after sending, and the confirmation email. No footer card on this page: the form is the call to action.

---

## SEO
Status: approved

Meta title: Register for a Programme | JoDyn Advisory and Consulting
Meta description: Register for an upcoming JoDyn training programme. Complete the short form to secure your seat and receive payment and joining details.
Search visibility: noindex (the programme pages should rank on Google, not the form)

---

## 1. Hero
Status: approved

Heading: Register for a Training Programme
Text: Secure your seat in a few simple steps. Complete the form below, and we will send your confirmation, payment details and programme information by email.

---

## 2. How registration works
Status: approved

1. Complete the form: Choose your programme and enter your details. It takes about two minutes.
2. Receive confirmation: You will receive an email with your registration details and payment information.
3. Make payment: Your seat is secured once payment is received.
4. Get ready: Joining instructions, venue details or online links are sent before the programme starts.

---

## 3. Registration form
Status: approved

Programme details
- Programme * (dropdown of upcoming programmes, pre-selected when the visitor clicked Register Now)
- Preferred format * (only for hybrid programmes: In-person / Online)

Registration type
- I am registering * (As an individual / On behalf of an organisation)

Participant details
- Full name *
- Email address *
- Phone number (WhatsApp preferred) *
- Job title
- Organisation (required if registering for an organisation)

Organisation registrations only
- Number of participants *
- Additional participants (name, email, job title for each)
- Contact person for invoicing (name, email and phone)

Additional information
- How did you hear about us? (LinkedIn, Instagram, Facebook, Google search, Referral, Email, Other)
- Any special requirements? (for example, accessibility or dietary needs)

Agreements
- Checkbox *: I agree to the Terms of Use and the cancellation and refund policy, and I have read the Privacy Policy. (each linked)
- Checkbox (optional): Send me updates on new programmes and insights.

Button: Complete Registration
Help line: Need help? Call or WhatsApp [phone number] or email [training@jodynconsulting.com]

Summary box (beside the form, below it on phones): programme title, date, format, venue and fee.

> Changed: "Number of participants" sets how many additional-participant rows appear (2 participants = you plus one more row), so the two always match.
> Changed: for organisations the summary box also shows the total (fee × participants). When the group is large enough for the group discount (Customize → Training calendar), it says so: "Group discount applies: [X]% off". [Confirm with the client: the discount is applied on the invoice, not taken automatically.]
> Changed: fully booked runs are left out of the dropdown. If nothing is open, the form is replaced by: "No programmes are open for registration right now." with buttons View Training Calendar and Request a Programme (→ /contact-us/?enquiry=training). (approved 2026-10-24)
> Build note: every Register Now button on the site (home, Training, calendar) goes to /register/?programme=<id>, so the programme is pre-selected.
> Build note: each registration is saved in the admin (a new "Registrations" list, like Calendar downloads) and emailed to the training team.
> [PLACEHOLDER] the phone number (the footer's number from Customize is used once it is filled in) and the training email address. The Terms of Use, refund policy and Privacy Policy links go to the legal pages, which are still to be built.

---

## 4. On-screen confirmation
Status: approved

Shown in place of the form straight after it is sent.

Heading: Thank you for registering!
Text: Your registration for [Programme Title] has been received. We have sent a confirmation email to [email address] with your payment details and next steps.
Text: Can't find the email? Please check your spam or promotions folder, or contact us at [training@jodynconsulting.com].
Button: Back to Training Calendar → /training/calendar/

> [Programme Title] and [email address] are filled in from the form; [training@jodynconsulting.com] is a placeholder.

---

## 5. Confirmation email
Status: approved

Sent automatically to the participant, with the training team copied in so they can follow up.

Subject: Your registration for [Programme Title] is received.

Dear [First Name],

Thank you for registering for [Programme Title] with JoDyn Advisory and Consulting. We are delighted to have you.

**Programme details**
Programme: [Programme Title]
Date: [Date] · Time: [Time]
Format: [In-person, Venue / Online]
Fee: ₦[Amount] [per participant / total for X participants]

**Payment details**
Bank: [Bank name] · Account name: [Account name] · Account number: [Account number]
Please use your full name or organisation name as the payment reference, and send proof of payment to [training@jodynconsulting.com]. Your seat is confirmed once payment is received.

**What happens next?**
Once payment is confirmed, we will send your joining instructions [X] days before the programme.

If you have any questions, simply reply to this email or call [phone number].

Warm regards,
The JoDyn Training Team
JoDyn Advisory and Consulting

> Build note: the bank details, training email and the [X] days are set in Customize → Training registration, so the client can fill them in without code. Programme details come from the programme.
> Build note: the time comes from a new "Time" field on each programme (for example 9:00am – 4:00pm).
> Build note: the email needs the live site to send email (an SMTP plugin), as with the calendar download. [Confirm with the client]
