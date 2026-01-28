# Quick Setup Guide

## 🚀 Get Started in 5 Minutes

### 1. Install Backend
```bash
cd backend
npm install
```

### 2. Setup Backend .env
Create `backend/.env`:
```
MONGODB_URI=mongodb://localhost:27017/alliedeals
JWT_SECRET=mysecretkey123
RAZORPAY_KEY_ID=your_razorpay_key
RAZORPAY_KEY_SECRET=your_razorpay_secret
```

### 3. Start Backend
```bash
npm run dev
```

### 4. Install Frontend
```bash
cd frontend
npm install
```

### 5. Setup Frontend .env
Create `frontend/.env.local`:
```
NEXT_PUBLIC_API_URL=http://localhost:5000
NEXT_PUBLIC_RAZORPAY_KEY_ID=your_razorpay_key
```

### 6. Start Frontend
```bash
npm run dev
```

### 7. Access
- Frontend: http://localhost:3000
- Backend: http://localhost:5000

## 📝 To Do
1. Update Razorpay keys in both .env files
2. Ensure MongoDB is running
3. Create sample products via API or directly in MongoDB

## 🧪 Test Payment
Use Razorpay test card: 4111 1111 1111 1111

Enjoy! 🎉
