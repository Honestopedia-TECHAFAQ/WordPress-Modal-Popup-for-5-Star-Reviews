<?php
$apiKey = 'YOUR_API_KEY';
$placeId = 'YOUR_PLACE_ID';
$reviews = [];
$googleReviews = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&fields=reviews&key=$apiKey");
$googleReviews = json_decode($googleReviews, true);

if (isset($googleReviews['result']['reviews'])) {
    foreach ($googleReviews['result']['reviews'] as $review) {
        if ($review['rating'] == 5) {
            $reviews[] = [
                'author_name' => $review['author_name'],
                'profile_photo_url' => $review['profile_photo_url'] ?? 'https://via.placeholder.com/50',
                'text' => $review['text'],
                'rating' => $review['rating']
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Reviews Modal</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            font-size: 16px;
            color: #333;
        }

        .modal {
            display: none; 
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            padding: 20px;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .close:hover,
        .close:focus {
            color: #333;
            cursor: pointer;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .review {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 5px solid #ff8c00;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .review:hover {
            background-color: #f1f1f1;
        }

        .review img {
            border-radius: 50%;
            margin-right: 15px;
        }

        .review-content {
            flex: 1;
        }

        .review strong {
            font-size: 18px;
            color: #333;
        }

        .review p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin-top: 5px;
        }

        .stars {
            color: #f8c200;
            font-size: 18px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modal-content {
                padding: 20px;
                width: 95%;
            }

            h2 {
                font-size: 20px;
            }

            .review strong {
                font-size: 16px;
            }

            .review p {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .modal-content {
                width: 100%;
                padding: 15px;
            }

            h2 {
                font-size: 18px;
            }

            .review strong {
                font-size: 14px;
            }

            .review p {
                font-size: 12px;
            }

            .close {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    <!-- Modal Structure -->
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Our 5-Star Reviews</h2>
            <div id="reviews">
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <img src="<?php echo htmlspecialchars($review['profile_photo_url']); ?>" alt="Profile Image" width="50" height="50">
                        <div class="review-content">
                            <strong><?php echo htmlspecialchars($review['author_name']); ?></strong>
                            <div class="stars">
                                <?php
                                for ($i = 0; $i < 5; $i++) {
                                    echo ($i < $review['rating']) ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <p><?php echo htmlspecialchars($review['text']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            var modal = document.getElementById("reviewModal");
            var span = document.getElementsByClassName("close")[0];

            modal.style.display = "block";
            span.onclick = function() {
                modal.style.display = "none";
            }
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    </script>
    
</body>
</html>
