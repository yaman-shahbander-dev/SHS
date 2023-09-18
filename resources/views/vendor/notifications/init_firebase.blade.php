var firebaseConfig = {
  apiKey: "{{setting('firebase_api_key','AIzaSyC1GWjZ1Irhj7_OB4Ob--_a_rcP0xnk1Js')}}",
  authDomain: "{{setting('firebase_auth_domain','shs-chat-c425e.firebaseapp.com')}}",
  databaseURL: "{{setting('firebase_database_url','https://shs-chat-c425e-default-rtdb.firebaseio.com/')}}",
  projectId: "{{setting('firebase_project_id','shs-chat-c425e')}}",
  storageBucket: "{{setting('firebase_storage_bucket','shs-chat-c425e.appspot.com')}}",
  messagingSenderId: "{{setting('firebase_messaging_sender_id','963124896977')}}",
  appId: "{{setting('firebase_app_id','1:963124896977:web:016e3a562edc51652211f0')}}",
  measurementId: "{{setting('firebase_measurement_id','G-2MVVRHDF8M')}}"
};



// Initialize Firebase
firebase.initializeApp(firebaseConfig);