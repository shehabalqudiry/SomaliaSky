  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.9.1/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.9.1/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyBtJddxVCaoapD9h8r1gkvvhP7jaA8aR6E",
    authDomain: "somaliasky-c0bbf.firebaseapp.com",
    projectId: "somaliasky-c0bbf",
    storageBucket: "somaliasky-c0bbf.appspot.com",
    messagingSenderId: "924263313578",
    appId: "1:924263313578:web:635c994eaf221ff629a0d3",
    measurementId: "G-G39WXMWR6G"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
