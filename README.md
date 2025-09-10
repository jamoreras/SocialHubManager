# Social Media Scheduler

A Laravel-based web application for scheduling and publishing posts to multiple social media platforms (LinkedIn, Reddit, Tumblr) using OAuth 2.0 integration.

## ✨ Features

- **User Authentication**: Secure registration/login system with password reset functionality  
- **OAuth Integration**: Connect to LinkedIn, Reddit, and Tumblr APIs  
- **Post Scheduling**: Schedule content for specific dates and times  
- **Calendar Interface**: Visual calendar for managing scheduled posts  
- **Protected Access**: Middleware ensures only authenticated users can access features  

## 🛠️ Tech Stack

- **Backend**: Laravel 8.x/9.x (PHP)  
- **Database**: MySQL  
- **Authentication**: Laravel Auth  
- **Frontend**: Blade templates, JavaScript, CSS  
- **APIs**: LinkedIn API, Reddit API, Tumblr API  

## 🚀 Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/jamoreras/SocialHubManager.git
   cd SocialHubManager
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Set up environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure environment variables in `.env`**:
   - Database credentials  
   - Social media API keys:
     ```
     LINKEDIN_CLIENT_ID
     LINKEDIN_CLIENT_SECRET
     REDDIT_CLIENT_ID
     REDDIT_CLIENT_SECRET
     TUMBLR_CLIENT_ID
     TUMBLR_CLIENT_SECRET
     ```

5. **Run migrations**:
   ```bash
   php artisan migrate
   ```

6. **Start development server**:
   ```bash
   php artisan serve
   ```

## 📝 Usage

- Register or login to your account  
- Connect social media accounts via OAuth  
- Create and schedule posts for future publishing  
- Manage scheduled posts through calendar interface  

## 🔧 API Endpoints

- `GET /home` - User dashboard  
- `POST /horario` - Create scheduled post  
- `PUT /horario/{id}` - Update scheduled post  
- `DELETE /horario/{id}` - Delete scheduled post  

## 📄 License

MIT License - see LICENSE file for details