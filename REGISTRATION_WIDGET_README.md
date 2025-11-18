# LCCI Registration Widget for Elementor

A comprehensive WordPress plugin that provides a multi-step registration form widget for Elementor with integrated payment status tracking, user management, and admin dashboard.

## Features

### 🎨 Frontend Registration Widget
- **Multi-step form** with validation and progress indicators
- **Step 1: Personal Details** - Title, name, contact info, email, password
- **Step 2: Company Details** - Company info, sector, address, reason for joining
- **Step 3: Payment Method** - Choose between Card or Direct Debit with automatic pricing
- **Real-time form validation** with password requirements
- **Responsive design** for all devices
- **Customizable colors** and text from Elementor settings

### 💾 Database Integration
- **Custom database tables** for members and payments
- **Automatic table creation** on plugin activation
- User data stored in WordPress users table
- Member details stored in custom `wp_tocc_members` table
- Payment information tracked in `wp_tocc_payments` table

### 👥 User Management
- **WordPress user creation** with registration
- **Unique email validation** 
- **User roles** automatically assigned (subscriber)
- **Secure password storage** using WordPress hashing
- **User meta** for extended profile information

### 💳 Payment Tracking
- **Two payment methods**: Card (£732/year) and Direct Debit (£666/year)
- **Payment status tracking**: pending, completed, failed
- **Transaction ID storage** for payment reconciliation
- **Admin dashboard** to view payment statuses

### 🔐 Admin Dashboard
- **Members list** with search and pagination
- **Payment status filters**: All, Pending, Completed
- **Member details view** with company and contact information
- **Quick actions** for payment status updates
- **Export ready** for integrations

### 📧 Notifications
- **Welcome emails** sent to new members
- **Admin notifications** for new registrations
- **Customizable email templates**

## Database Tables

### `wp_tocc_members`
```
- id (Primary Key)
- user_id (Foreign Key to wp_users)
- company_name
- company_website
- company_description
- sector
- employee_count
- address_1, address_2
- city
- postcode
- country
- reason_for_joining
- created_at
- updated_at
```

### `wp_tocc_payments`
```
- id (Primary Key)
- user_id (Foreign Key to wp_users)
- payment_method (card, direct_debit)
- amount (Decimal)
- currency (GBP)
- status (pending, completed, failed)
- transaction_id
- notes
- created_at
- updated_at
```

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin in WordPress admin
3. Database tables are created automatically
4. Add "TOCC Registration Form" widget to any Elementor page

## Usage

### Adding Widget to Page

1. Edit page with Elementor
2. Search for "TOCC Registration Form" widget
3. Drag widget to desired location
4. Customize in widget settings:
   - Form title and subtitle
   - Support contact info
   - Payment amounts
   - Colors (primary, accent, background)

### Customizing Form Settings

In widget settings, you can customize:

```php
// Form Content
- Form Title
- Form Subtitle
- Support Phone Number
- Support Email

// Payment Settings
- Card Payment Cost (default: £732.00)
- Direct Debit Cost (default: £666.00)

// Styling
- Primary Color
- Accent Color
- Background Color
```

### Viewing Registrations

1. Go to WordPress Admin > LCCI Registrations
2. View all members with their details
3. Check payment status
4. Navigate to submenus:
   - **All Members** - Complete list of registrations
   - **Pending Payments** - Members awaiting payment
   - **Completed Payments** - Successfully paid members

## API Reference

### Get Member Details
```php
$member = TOCC_Registration_Handler::get_member($user_id);
echo $member->company_name;
echo $member->sector;
```

### Get Payment Info
```php
$payment = TOCC_Registration_Handler::get_payment($user_id);
echo $payment->status; // pending, completed, failed
echo $payment->amount;
echo $payment->payment_method;
```

### Update Payment Status
```php
TOCC_Registration_Handler::update_payment_status($user_id, 'completed', 'TXN123456');
```

### Get All Members
```php
$members = TOCC_Registration_Handler::get_all_members($limit = 50, $offset = 0);
foreach ($members as $member) {
    echo $member->user_email;
    echo $member->company_name;
    echo $member->payment_status;
}
```

### Count Members
```php
$total = TOCC_Registration_Handler::count_members();
```

## File Structure

```
plugin-tocc-content/
├── tabbed-usp-widget.php          # Main plugin file
├── includes/
│   ├── registration-handler.php   # User & payment handling
│   └── admin-dashboard.php        # Admin pages
├── widgets/
│   ├── registration-widget-class.php # Widget class
│   ├── tabbed-usp-widget-class.php
│   ├── vertical-tabs-widget-class.php
│   ├── stats-section-widget-class.php
│   └── split-screen-slider-widget-class.php
├── admin/
│   ├── dashboard.php              # Main admin page
│   ├── pending-payments.php       # Pending payments view
│   └── completed-payments.php     # Completed payments view
└── assets/
    └── admin-style.css            # Admin styling
```

## Security

- **Nonce verification** for AJAX requests
- **Data sanitization** for all inputs
- **WordPress capabilities** check for admin pages
- **Password hashing** using WordPress functions
- **Email validation** before registration
- **CSRF protection** via WordPress nonces

## Integration with Payment Gateways

After form submission, payments are marked as "pending". To integrate with payment gateways (Stripe, PayPal, etc.):

1. Hook into payment processing
2. Update status using: `TOCC_Registration_Handler::update_payment_status($user_id, 'completed', $transaction_id);`
3. Payment status will be immediately visible in admin dashboard and user account

Example Integration:
```php
// Hook into payment gateway webhook
add_action('wc_api_tocc_payment_webhook', function() {
    $user_id = $_POST['user_id'];
    $transaction_id = $_POST['transaction_id'];
    
    TOCC_Registration_Handler::update_payment_status($user_id, 'completed', $transaction_id);
});
```

## Email Templates

Welcome email sent to new members includes:
- Welcome message with user's first name
- Link to login page
- Account confirmation

Customize email in `registration-handler.php` `send_welcome_email()` method.

## Support

For issues or feature requests, contact the development team or check the plugin documentation.

## Version History

- **2.3.0** - Added Registration Widget with payment tracking
- **2.2.0** - Previous versions with Tabbed USP, Vertical Tabs, Stats Section widgets

## License

GPL v2 or later
