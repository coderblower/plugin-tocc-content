# Quick Setup Guide - TOCC Registration Widget

## 🚀 What's New

All 4 issues have been fixed:

1. ✅ **Page Scroll Fixed** - Page stays in place when clicking "Continue"
2. ✅ **Dropdown Design Fixed** - Clean dropdown with proper arrow alignment
3. ✅ **Custom Login Redirect** - Redirect to any URL after payment
4. ✅ **Stripe Integration** - Full card payment processing

---

## 📋 Setup Steps

### Step 1: Add Stripe API Keys (WordPress Admin)

1. Go to: **WordPress Admin > LCCI Registrations > Stripe Settings**
2. Enter your **Secret Key** from your Stripe Dashboard (get it at https://dashboard.stripe.com/test/apikeys)
3. Enter your **Publishable Key** from your Stripe Dashboard
4. Click **Save Stripe Settings**

### Step 2: Configure Widget in Elementor

1. Edit a page with Elementor
2. Add/Select **TOCC Registration Form** widget
3. In widget settings:
   - **Stripe Publishable Key**: Add your test key from Stripe Dashboard
   - **Login Redirect URL**: `/login` (or your custom URL)
4. Update the page

### Step 3: Test the Form

1. **Frontend**: Fill the registration form
2. **Step 1**: Enter personal details (verify passwords match)
3. **Step 2**: Enter company details
4. **Step 3**: Choose payment method:
   - **Direct Debit**: £666/year (completes immediately)
   - **Card**: £732/year (opens Stripe payment modal)

### Step 4: Test Card Payment

Use test card: **4242 4242 4242 4242**
- Expiry: Any future date (e.g., 12/25)
- CVC: Any 3 digits (e.g., 123)

---

## 🎯 How It Works

### User Flow

```
Registration Form (3 Steps)
    ↓
Step 1: Personal Details
    - Title, Name, Email, Password
    - Password validation (8+ chars, uppercase, lowercase, special char)
    ↓
Step 2: Company Details
    - Company info, Sector, Address
    - Terms & Conditions agreement
    ↓
Step 3: Payment Method
    - Direct Debit: £666/year (10% discount)
    - Card: £732/year
    ↓
Payment Processing
    - Card: Stripe Payment Element
    - Direct Debit: Direct registration
    ↓
User Created & Payment Recorded
    ↓
Redirect to Custom URL (/login)
```

---

## 💳 Stripe Payment Method

### What Happens When User Selects "Card"

1. Form data sent to server
2. Server creates **Stripe Payment Intent**
3. Client secret returned to frontend
4. Stripe Payment Element opens in overlay
5. User enters card details securely in Stripe modal
6. Stripe processes payment
7. Server confirms payment
8. User registered in WordPress
9. Redirects to login page

### Security

- Secret key stored securely on server (not exposed)
- Publishable key safe to use on frontend
- Stripe handles PCI compliance
- All data validated server-side

---

## 📊 Admin Dashboard

### View Registrations

**WordPress Admin > LCCI Registrations**

- **All Members**: Complete list of registrations
- **Pending Payments**: Awaiting card payment
- **Completed Payments**: Successful transactions
- **Stripe Settings**: Configure API keys

### Member Information Tracked

- Personal details (name, email, phone, job title)
- Company information (name, sector, size, address)
- Payment method and status
- Transaction ID (from Stripe)
- Registration timestamp

---

## 🧪 Test Scenarios

### Scenario 1: Direct Debit Registration
- Select "Direct Debit" on Step 3
- Submit registration
- User created, payment marked as "pending"
- Redirect to custom URL

### Scenario 2: Card Payment (Success)
- Select "Card" on Step 3
- Submit registration
- Stripe modal opens
- Enter test card: 4242 4242 4242 4242
- Payment succeeds
- User created, payment marked as "completed"
- Redirect to custom URL

### Scenario 3: Card Payment (Decline)
- Select "Card" on Step 3
- Enter test card: 4000 0000 0000 0002 (decline card)
- Payment fails
- User sees error message
- Can try again

---

## 🔐 Production Checklist

Before going live:

- [ ] Switch to **Live Stripe Keys** (sk_live_, pk_live_)
- [ ] Update widget with live publishable key
- [ ] Test with real card (small amount)
- [ ] Set up Stripe webhooks
- [ ] Enable HTTPS on site
- [ ] Test email notifications
- [ ] Verify payment amounts correct
- [ ] Test login redirect URL
- [ ] Review privacy policy for payment info
- [ ] Set up backup payment method (if needed)

---

## 📞 Support

### Common Issues

**Q: Stripe modal not opening?**
- Check publishable key is correct
- Check Stripe account is active

**Q: Payment succeeds but user not created?**
- Check server logs
- Verify form data is valid
- Ensure email doesn't already exist

**Q: Custom redirect not working?**
- Check URL format (should start with /)
- Verify URL exists on site
- Check for redirect plugins interfering

**Q: Dropdown arrow broken?**
- Clear browser cache
- Hard refresh (Ctrl+F5)
- Update widget

---

## 📝 File Changes

Updated Files:
- `widgets/registration-widget-class.php` - Widget with Stripe + scroll fix + dropdown fix
- `includes/registration-handler.php` - Stripe payment intent creation
- `includes/stripe-settings.php` - New Stripe settings page
- `includes/admin-dashboard.php` - Admin menu updated

New Documentation:
- `FIXES_AND_ENHANCEMENTS.md` - Detailed fix information
- `QUICK_SETUP.md` - This file

---

## ✨ Features Summary

✅ Multi-step registration form (3 steps)
✅ Database storage for all data
✅ WordPress user creation
✅ Two payment methods (Card + Direct Debit)
✅ Stripe card payment integration
✅ Payment status tracking
✅ Admin dashboard
✅ Custom login redirect
✅ Email notifications
✅ Password validation
✅ Form validation
✅ Responsive design
✅ Customizable colors
✅ No scroll on continue
✅ Clean dropdown design

---

Ready to go! 🚀
