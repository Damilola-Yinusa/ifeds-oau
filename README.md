# IFEDS OAU - MagicAI Application

A comprehensive AI-powered content generation platform built with Laravel.

## 🚀 Features

- **AI Content Generation**: Text, images, code, and more
- **Multi-AI Engine Support**: OpenAI, Anthropic, Gemini, and more
- **Chatbot Builder**: Create custom AI chatbots
- **Document Processing**: PDF, Word, and text analysis
- **Voice Generation**: Text-to-speech capabilities
- **User Management**: Complete user and team management
- **Payment Integration**: Multiple payment gateways
- **Multi-language Support**: Internationalization ready
- **Responsive Design**: Modern, mobile-friendly interface

## 🛠️ Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL 8.0+
- **PHP**: 8.2+
- **AI Integration**: OpenAI, Anthropic, Google AI, and more

## 📋 Requirements

- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer
- Node.js & NPM (for asset compilation)
- Web server (Apache/Nginx)

## 🚀 Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/ifeds-oau.git
cd ifeds-oau
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
# Create database
mysql -u root -e "CREATE DATABASE magicai;"

# Import database schema
mysql -u root magicai < ../magicai.sql

# Run migrations
php artisan migrate
```

### 5. Configure Environment
Update `.env` file with your database and AI service credentials:

```env
APP_NAME="IFEDS OAU"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=magicai
DB_USERNAME=root
DB_PASSWORD=

# AI Service Keys
OPENAI_API_KEY=your-openai-key
ANTHROPIC_API_KEY=your-anthropic-key
GEMINI_API_KEY=your-gemini-key
```

### 6. Set Permissions
```bash
chmod -R 755 storage/ bootstrap/cache/
```

### 7. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 🌐 Production Deployment

### Azure Deployment
This application is configured for Azure deployment. See the deployment guide:

```bash
# Run Azure deployment script
./deploy-to-azure.sh
```

### Manual Deployment
1. Upload files to your web server
2. Set up database and import schema
3. Configure environment variables
4. Run `composer install --no-dev --optimize-autoloader`
5. Run `php artisan config:cache`
6. Set proper file permissions

## 📁 Project Structure

```
├── app/                    # Application logic
│   ├── Actions/           # Business logic actions
│   ├── Domains/           # Domain-specific code
│   ├── Http/              # Controllers and middleware
│   ├── Models/            # Eloquent models
│   └── Services/          # Service classes
├── config/                # Configuration files
├── database/              # Migrations and seeders
├── public/                # Public assets
├── resources/             # Views, assets, and language files
├── routes/                # Route definitions
└── storage/               # Application storage
```

## 🔧 Configuration

### AI Services
Configure your AI service API keys in the `.env` file:

```env
OPENAI_API_KEY=your-openai-key
ANTHROPIC_API_KEY=your-anthropic-key
GEMINI_API_KEY=your-gemini-key
STABLE_DIFFUSION_API_KEY=your-stable-diffusion-key
```

### Payment Gateways
Configure payment gateways in the admin panel or environment variables:

```env
STRIPE_KEY=your-stripe-key
STRIPE_SECRET=your-stripe-secret
PAYPAL_CLIENT_ID=your-paypal-client-id
PAYPAL_SECRET=your-paypal-secret
```

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## 📊 Monitoring

- Application logs: `storage/logs/`
- Database monitoring via admin panel
- Performance monitoring with Laravel Telescope

## 🔒 Security

- CSRF protection enabled
- SQL injection prevention
- XSS protection
- Rate limiting
- Input validation and sanitization

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License.

## 🆘 Support

For support and questions:
- Check the documentation
- Review the issues section
- Contact the development team

## 🚀 Deployment Status

- **Local Development**: ✅ Ready
- **GitHub Repository**: ✅ Ready
- **Azure Deployment**: ✅ Configured
- **Production**: 🚧 In Progress

---

**Built with ❤️ for IFEDS OAU**
