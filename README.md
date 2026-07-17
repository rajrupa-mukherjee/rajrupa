# Rajrupa Mukherjee Website

A modern, professional website for Rajrupa Mukherjee, renowned Bharatanatyam dancer and teacher from Kolkata, India.

## Features

- **Modern Design**: Clean, classical aesthetic with responsive layout
- **Content Management**: Dynamic content management system
- **Gallery System**: Photo and video gallery with categorization
- **Performance Timeline**: Interactive timeline showcasing performances
- **Contact System**: Contact form with email notifications
- **SEO Optimized**: Meta tags, structured data, and semantic HTML
- **Mobile Responsive**: Works perfectly on all devices
- **Admin Panel**: Content management interface (planned)

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 8.0+
- **Database**: MySQL/MariaDB
- **Styling**: Custom CSS with responsive design
- **Fonts**: Google Fonts (Playfair Display, Crimson Text, Montserrat)
- **Icons**: Unicode symbols and CSS-based icons

## Project Structure

```
rajrupa/
├── index.html                 # Homepage
├── performances.html           # Performances page
├── awards.html               # Awards & Recognitions page
├── training.html              # Training & Workshops page
├── guru.html                 # Her Guru page
├── gallery.html              # Photo Gallery page
├── videos.html               # Video Gallery page
├── contact.html              # Contact page
├── press.html                # Press Clippings page
├── comments.html             # Comments/Testimonials page
├── css/
│   ├── style.css             # Main stylesheet
│   └── responsive.css        # Responsive styles
├── js/
│   └── script.js             # Main JavaScript file
├── images/                   # Image assets
├── uploads/                  # User uploaded content
├── config.php                # Configuration file
├── functions.php             # Helper functions
├── database.sql              # Database schema
└── README.md                 # This file
```

## Installation

### Prerequisites

- PHP 8.0 or higher
- MySQL/MariaDB
- Web server (Apache/Nginx)
- XAMPP/WAMP/MAMP (for local development)

### Setup Instructions

1. **Clone/Download the Project**
   ```bash
   git clone <repository-url>
   cd rajrupa
   ```

2. **Database Setup**
   - Create a new database named `rajrupa_website`
   - Import the `database.sql` file to create tables and sample data
   ```sql
   CREATE DATABASE rajrupa_website;
   USE rajrupa_website;
   SOURCE database.sql;
   ```

3. **Configure Database**
   - Open `config.php`
   - Update database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'rajrupa_website');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

4. **Set Up Directories**
   - Create the following directories with proper permissions:
   ```bash
   mkdir uploads
   mkdir uploads/gallery
   mkdir uploads/press
   mkdir logs
   mkdir cache
   chmod 755 uploads
   chmod 755 logs
   chmod 755 cache
   ```

5. **Configure Web Server**
   - Point your web server document root to the project directory
   - Ensure `.htaccess` is properly configured for Apache

6. **Test the Installation**
   - Open your browser and navigate to `http://localhost/rajrupa`
   - Verify all pages load correctly

## Configuration

### Site Settings

Update the following settings in `config.php`:

```php
// Basic Settings
define('SITE_NAME', 'Rajrupa Mukherjee');
define('SITE_URL', 'http://localhost/rajrupa');
define('SITE_EMAIL', 'rajrupa.m79@gmail.com');

// Security Settings
define('ENCRYPTION_KEY', 'your-secret-key-here');
define('SESSION_LIFETIME', 3600);

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
```

### Email Configuration

For contact forms to work, configure SMTP settings:

```php
define('SMTP_HOST', 'your-smtp-host');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email');
define('SMTP_PASSWORD', 'your-password');
```

## Pages Overview

### 1. Homepage (`index.html`)
- Hero section with professional imagery
- About section with biography
- Contact information
- Navigation to all sections

### 2. Performances (`performances.html`)
- Interactive timeline of performances
- Chronological display from 1994 to present
- Venue and organizer information

### 3. Awards & Recognitions (`awards.html`)
- List of awards and recognitions
- Year-wise organization
- Featured awards section

### 4. Training & Workshops (`training.html`)
- Information about dance classes
- Workshop schedules
- Training programs details

### 5. Her Guru (`guru.html`)
- Information about Guru Shri Khagendranath Barman
- Guru lineage and tradition
- Teaching philosophy

### 6. Gallery (`gallery.html`)
- Photo gallery with categorization
- Responsive image display
- Lightbox functionality

### 7. Videos (`videos.html`)
- Video gallery of performances
- Embedded video player
- Performance clips

### 8. Contact (`contact.html`)
- Contact form with validation
- Contact information display
- Map integration (optional)

## Database Schema

The database includes the following main tables:

- `performances` - Performance history
- `awards` - Awards and recognitions
- `training_workshops` - Training programs
- `gallery_items` - Photos and videos
- `gallery_categories` - Gallery categories
- `press_clippings` - Press coverage
- `comments` - User comments and testimonials
- `guru_info` - Guru information
- `contact_messages` - Contact form submissions
- `users` - Admin users
- `site_settings` - Site configuration

## Customization

### Adding New Content

1. **Performances**: Add via database or admin panel
2. **Gallery Items**: Upload to `uploads/gallery/` and add to database
3. **Awards**: Add to `awards` table
4. **Training Programs**: Add to `training_workshops` table

### Styling

- Main styles in `css/style.css`
- Responsive styles in `css/responsive.css`
- Color scheme: Classical brown/gold theme
- Typography: Playfair Display (headings), Crimson Text (body), Montserrat (navigation)

### JavaScript Features

- Mobile navigation toggle
- Smooth scrolling
- Form validation
- Image lazy loading
- Interactive elements

## SEO Features

- Semantic HTML5 structure
- Meta tags for all pages
- Open Graph tags
- Structured data (microdata)
- Clean URLs
- Mobile-friendly design
- Fast loading optimization

## Security Features

- Input sanitization
- CSRF protection
- SQL injection prevention
- File upload validation
- Session management
- Rate limiting (planned)

## Performance Optimization

- Image lazy loading
- CSS/JS minification (planned)
- Caching system
- Optimized database queries
- Compressed assets

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## Support

For support and questions:

- Email: rajrupa.m79@gmail.com
- Phone: +91 98317 39628
- Address: 58, Ashokegarh (Dunlop Bridge) Kolkata 700 108

## License

This project is proprietary and belongs to Rajrupa Mukherjee. All rights reserved.

## Credits

- Design & Development: Kreation (www.kreation4u.com)
- Website Designers India (www.websitedesignersindia.in)

---

**Note**: This is a professional website showcasing the artistic journey of Rajrupa Mukherjee in Bharatanatyam dance. Please respect the artistic content and intellectual property rights.
