<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery - Genti Production</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- bootstrap links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- fonts links -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      background-color: #f4f4f4;
      width: 100%;
    }

    h1 {
      margin: 20px;
      color: #333;
    }

    #gallery {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
      margin-bottom: 20px;
      width: 100%;
    }

    .thumbnail {
      cursor: pointer;
      border: 2px solid transparent;
      transition: border-color 0.3s;
    }

    .thumbnail:hover {
      border-color: #007bff;
    }

    #canvasContainer {
      text-align: center;
      position: relative;
      width: 100%;
    }

    canvas {
      border: 1px solid #333;
    }

    #controls {
      margin-top: 10px;
    }

    button {
      padding: 10px 20px;
      margin: 0 5px;
      font-size: 1rem;
      cursor: pointer;
      border: none;
      border-radius: 5px;
      background-color: #007bff;
      color: white;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #0056b3;
    }

    #gallery,
    #favorites {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      border: 2px dashed #ccc;
      padding: 10px;
      min-height: 120px;
      width: 100%;
    }

    .photo {
      width: 100px;
      height: 100px;
      border: 2px solid #333;
      border-radius: 5px;
    }

    #download-btn {
      display: inline-block;
      padding: 10px 15px;
      font-size: 1rem;
      background-color: #28a745;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #download-btn:hover {
      background-color: #218838;
    }
  </style>
</head>

<body>
  <div class="all-content">

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg" id="navbar">
      <div class="container-fluid">
        <a class="navbar-brand" href="#" id="logo"><img src="./images/logo.png" alt="Genti Production"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span><i class="fa-solid fa-bars" style="color: white; font-size: 23px;"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="services.php">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="gallery.php">Gallery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="blogs.php">Blogs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- navbar -->

    <!-- gallery section -->
    <div class="container" id="gallery">
      <h1>Our <span>Gallery</span></h1>
      <div class="row" style="margin-top: 30px;">
        <div class="col-md-4 py-3 py-md-0">
          <div class="card">
            <img src="./images/wedding1.jpg" alt="Wedding 1" style="height: 180px; object-fit: cover;">
          </div>
        </div>
        <div class="col-md-4 py-3 py-md-0">
          <div class="card">
            <img src="./images/wedding2.jpg" alt="Wedding 2" style="height: 180px; object-fit: cover;">
          </div>
        </div>
        <div class="col-md-4 py-3 py-md-0">
          <div class="card">
            <img src="./images/wedding3.jpg" alt="Wedding 3" style="height: 180px; object-fit: cover;">
          </div>
        </div>
      </div>
      <div class="row" style="margin-top: 30px;">
        <div class="col-md-4 py-3 py-md-0">
          <div class="card">
            <img src="./images/wedding4.jpg" alt="Wedding 4" style="height: 180px; object-fit: cover;">
          </div>
        </div>
        <div class="col-md-4 py-3 py-md-0">
          <div class="card">
            <img src="./images/wedding5.jpg" alt="Wedding 5" style="height: 180px; object-fit: cover;">
          </div>
        </div>
        <div class="col-md-4 py-3 py-md-0">
          <div class="card">
            <img src="./images/wedding6.jpg" height="520px" alt="Wedding 6" style="height: 180px; object-fit: cover;">
          </div>
        </div>
      </div>
    </div>
    <!-- gallery section -->
    <h3 align="center">Interactive Gallery</h3>
    <div id="gallery-interactive">
      <!-- Thumbnails -->


    </div>
    <div id="canvasContainer">
      <!-- Canvas for larger view -->
      <canvas id="galleryCanvas" width="500" height="500"></canvas>
      <div id="controls">
        <!-- Navigation buttons -->
        <button id="prevButton">Previous</button>
        <button id="nextButton">Next</button>
      </div>
    </div>


    <script>
      const canvas = document.getElementById('galleryCanvas');
      const ctx = canvas.getContext('2d');

      const thumbnails = document.querySelectorAll('.thumbnail');
      const prevButton = document.getElementById('prevButton');
      const nextButton = document.getElementById('nextButton');

      // Array of image sources
      const images = [
        'images/wedding1.jpg', // Replace with your image paths
        'images/videography.jpg',
        'images/wedding2.jpg',
        'images/wedding7.jpg',
        'images/wedding3.jpg',
        'images/wedding5.jpg',

      ];

      let currentIndex = 0;

      // Function to load and draw an image on the canvas
      function loadImage(index) {
        const img = new Image();
        img.src = images[index];
        img.onload = () => {
          ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas
          const aspectRatio = img.width / img.height;
          let width, height;
          if (canvas.width / canvas.height > aspectRatio) {
            height = canvas.height;
            width = height * aspectRatio;
          } else {
            width = canvas.width;
            height = width / aspectRatio;
          }
          const xOffset = (canvas.width - width) / 2;
          const yOffset = (canvas.height - height) / 2;
          ctx.drawImage(img, xOffset, yOffset, width, height);
        };
      }

      // Event listener for thumbnail clicks
      thumbnails.forEach((thumbnail) => {
        thumbnail.addEventListener('click', (e) => {
          currentIndex = parseInt(thumbnail.dataset.index, 10);
          loadImage(currentIndex);
        });
      });

      // Navigation button functionality
      prevButton.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        loadImage(currentIndex);
      });

      nextButton.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % images.length;
        loadImage(currentIndex);
      });

      // Load the first image initially
      loadImage(currentIndex);
    </script> <br>

    <h2 align="center">Let us know what you`re looking for!</h2>
    <p align="center">From the images below drag your favourites into the box, and send them to us with your request,
      we will get back in touch with you shortly.
    </p>
    <div id="gallery">
      <img src="images/wedding5.jpg" alt="Photo 1" class="photo" draggable="true">
      <img src="images/wedding3.jpg" alt="Photo 2" class="photo" draggable="true">
      <img src="images/wedding7.jpg" alt="Photo 3" class="photo" draggable="true">
      <img src="images/wedding1.jpg" alt="Photo 4" class="photo" draggable="true">
      <img src="images/wedding2.jpg" alt="Photo 5" class="photo" draggable="true">
      <img src="images/videography.jpg" alt="Photo 6" class="photo" draggable="true">
    </div>

    <h2 align="center">Favorites</h2>
    <div id="favorites"></div>

    <button id="download-btn" align="center">Download Photo</button>

    <script>
      const gallery = document.getElementById('gallery');
      const favorites = document.getElementById('favorites');
      const downloadButton = document.getElementById('download-btn');

      gallery.addEventListener('dragstart', (e) => {
        if (e.target.classList.contains('photo')) {
          e.dataTransfer.setData('text/plain', e.target.src);
        }
      });

      favorites.addEventListener('dragover', (e) => {
        e.preventDefault();
      });

      favorites.addEventListener('drop', (e) => {
        e.preventDefault();
        const photoSrc = e.dataTransfer.getData('text/plain');
        if (photoSrc) {
          const newPhoto = document.createElement('img');
          newPhoto.src = photoSrc;
          newPhoto.alt = 'Favorite Photo';
          newPhoto.classList.add('photo');
          favorites.appendChild(newPhoto);
        }
      });

      downloadButton.addEventListener('click', () => {
        const photos = favorites.querySelectorAll('img');
        if (photos.length === 0) {
          alert('No photos in favorites to download!');
          return;
        }

        photos.forEach((photo, index) => {
          const link = document.createElement('a');
          link.href = photo.src;
          link.download = `favorite-photo-${index + 1}.jpg`;
          link.click();
        });

        alert('Photos have been downloaded!');
      });

      // Verifikim me regex ne inputin e kerkimit
      $("form").submit(function (e) {
        const searchText = $("input[type='search']").val();
        const pattern = /^[a-zA-Z0-9\s]+$/;

        if (!pattern.test(searchText)) {
          alert("Search input përmban karaktere të palejuara!");
          e.preventDefault();
        }
      });
    </script>


    <!-- footer -->
    <footer id="footer">
      <div class="footer-logo text-center">
        <img src="./images/logo.png" alt="Genti Production">
      </div>
      <div class="socail-links text-center">
        <a href="https://twitter.com" target="_blank">
          <i class="fa-brands fa-twitter"></i>
        </a>
        <a href="https://facebook.com" target="_blank">
          <i class="fa-brands fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank">
          <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="https://youtube.com" target="_blank">
          <i class="fa-brands fa-youtube"></i>
        </a>
        <a href="https://pinterest.com" target="_blank">
          <i class="fa-brands fa-pinterest-p"></i>
        </a>
      </div>

      <div class="credite text-center">
        Designed By <a href="#"> Grupi 7</a>
      </div>
      <div class="copyright text-center">
        &copy; Copyright <strong><span>Genti Production</span></strong>. All Rights Reserved
      </div>
    </footer>
    <!-- footer -->

    <a href="#" class="arrow"><i><img src="./images/up-arrow.png" alt="" width="50px"></i></a>

</body>

</html>