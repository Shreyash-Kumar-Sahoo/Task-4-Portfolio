<?php 
session_start();
include('star_db.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shreyash's E-Books</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <?php include('header.php'); ?>

    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">SHREYASH'S<br>E-BOOKS</h1>
                <a href="shop.php" class="hero-btn">UP TO 70% OFF</a>
                <p class="hero-subtext">*ON SELECT TITLES ALL NOVEMBER LONG</p>
            </div>
        </section>

        <!-- November Sale Section -->
        <section class="sale-section">
            <h2 class="section-title">NOVEMBER SALE</h2>
            <p class="section-subtitle">Shop by category</p>
            
            <div class="category-grid">
                <!-- Card 1 -->
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="website_image/cat1_img.png" alt="Paperbacks/Hardbound">
                    </div>
                    <div class="card-body">
                        <h3>PAPERBACKS/HARDBOUND</h3>
                        <p class="card-description">We proudly carry over one million book titles.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="website_image/cat2_img.png" alt="Electronic Books">
                    </div>
                    <div class="card-body">
                        <h3>ELECTRONIC BOOKS</h3>
                        <p class="card-description">An assortment of downloadable titles for your e-reader.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="category-card">
                    <div class="card-img-container">
                        <img src="website_image/cat3_img.png" alt="Music and Videos">
                    </div>
                    <div class="card-body">
                        <h3>MUSIC AND VIDEOS</h3>
                        <p class="card-description">We have a vast collection of records and DVDs.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Upcoming Events Section -->
        <section class="events-section">
            <div class="events-container">
                <div class="events-info">
                    <h2 class="events-title">UPCOMING EVENTS</h2>
                    
                    <div class="event-item">
                        <h3>POSTER-MAKING CONTEST</h3>
                        <p>Join our annual celebration of all things art and literature. Winners will be announced on August 31. Click here for more details.</p>
                    </div>
                    
                    <div class="event-item">
                        <h3>MEET AND GREET STELLA ORNELAS</h3>
                        <p>Award-winning poet Stella Ornelas is dropping by on March 10, 7 PM. She will be reading from her new collection, Spring, and signing copies. Buy tickets now!</p>
                    </div>
                    
                    <div class="event-item">
                        <h3>STORY TIME WITH FRIENDS</h3>
                        <p>Read books and spend time with kids for an afternoon in an event hosted by the Lily River Children's Foundation. Volunteer now.</p>
                    </div>
                </div>
                <div class="events-image">
                    <img src="website_image/upcoming_event_img.png" alt="Upcoming Events">
                </div>
            </div>
        </section>

        <!-- Physical Store Section -->
        <section class="store-section">
            <div class="store-container">
                <div class="store-image">
                    <img src="website_image/physical_store_img.png" alt="Physical Store">
                </div>
                <div class="store-info">
                    <div class="store-content">
                        <h2>WE NOW HAVE A<br>PHYSICAL<br>STORE</h2>
                        <p class="store-address">1234 Dairy Road<br>Meadowview, VA, USA 12345</p>
                        <a href="https://maps.google.com" target="_blank" class="store-btn">FIND ON MAPS</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Need Anything Section -->
        <section class="contact-section">
            <div class="contact-container">
                <div class="contact-info">
                    <div class="contact-content">
                        <h2>NEED ANYTHING?</h2>
                        
                        <div class="contact-item">
                            <h3>SOCIAL MEDIA</h3>
                            <p>@reallygreatsite</p>
                        </div>
                        
                        <div class="contact-item">
                            <h3>EMAIL ADDRESS</h3>
                            <p>hello@reallygreatsite.com</p>
                        </div>
                        
                        <div class="contact-item">
                            <h3>PHONE NUMBER</h3>
                            <p>(123) 456 7890</p>
                        </div>
                    </div>
                </div>
                <div class="contact-image">
                    <img src="website_image/need_anything_img.png" alt="Need Anything">
                </div>
            </div>
        </section>
    </main>

    <?php 
    // Safely check and include parent footer if it exists, otherwise render simple footer closing
    if (file_exists('../footer.php')) {
        include('../footer.php');
    } else {
        echo '<footer><div class="footer-container"><p>&copy; ' . date('Y') . ' Shreyash\'s E-Books</p></div></footer></body></html>';
    }
    ?>