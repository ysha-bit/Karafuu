<?php 
session_start();
// include('./includes/check_login.php');
include('../conn/config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$firstname = $lastname = $phone = $zanzibar_id = $amount_of_miche = $shehia = $wilaya = '';
$errors = array('firstname' => '', 'lastname' => '', 'phone' => '', 'zanzibar_id' => '', 'amount_of_miche' => '', 'shehia' => '', 'wilaya' => '');

if (isset($_POST['submit'])) {
    // Validate and sanitize inputs
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = "First name is required";
    } else {
        
    }

    if (empty($_POST['lastname'])) {
        $errors['lastname'] = "Last name is required";
    } else {
        
    }

    if (empty($_POST['phone'])) {
        $errors['phone'] = "Phone number is required";
    } else {
        
    }
    if (empty($_POST['zanzibar_id'])) {
        $errors['zanzibar_id'] = "Zanzibar ID is required";
    } else {
        
       
    }

    if (empty($_POST['amount_of_miche'])) {
        $errors['amount_of_miche'] = "Amount of cloves trees is required";
    } else {
        
    }
    if (empty($_POST['shehia'])) {
        $errors['shehia'] = "Amount of cloves trees is required";
    } else {
        
       
    }

    if (empty($_POST['wilaya'])) {
        $errors['wilaya'] = "Amount of cloves trees is required";
    } else {
        
       
    }

    if(!array_filter($errors)){
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $zanzibar_id = mysqli_real_escape_string($conn, $_POST['zanzibar_id']);
        $amount_of_miche = mysqli_real_escape_string($conn, $_POST['amount_of_miche']);
        $shehia = mysqli_real_escape_string($conn, $_POST['shehia']);
        $wilaya = mysqli_real_escape_string($conn, $_POST['wilaya']);
        $date = date('Y');

        $sql = "INSERT INTO farmers(firstname, lastname, phone, zanzibar_id, amount_of_miche, shehia, wilaya, date) VALUES('$firstname', '$lastname', '$phone', '$zanzibar_id', '$amount_of_miche', '$shehia', '$wilaya','$date')";

        $result = mysqli_query($conn, $sql);

        if(!$result){
            echo "Query Error:".mysqli_error($conn);
        }

    }else{
        echo "There is an error is form!";
    }

    

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Registration</title>
    <!-- Include MDB UI Kit CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.0.0/mdb.min.css" />
    <style>
        /* Scoping styles for the farmer registration form */

        body {
            background-color: #f0f0f0;
        }

        .farmer-registration-container .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            margin-left:20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, background-color 0.3s ease-in-out;
        }

        .farmer-registration-container .container:hover {
            transform: scale(1.02);
            background-color: #fafafa;
        }

        .farmer-registration-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .farmer-registration-container .card-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 355px; /* Increased height to display more fields */
            min-height: 300px;
        }

        .farmer-registration-container .card {
            position: absolute;
            top: 0;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            transition: transform 0.5s ease-in-out;
            overflow-y: auto;
            border-radius: 8px;
            background: linear-gradient(135deg, #fff, #f9f9f9);
            transform: translateX(100%);
        }

        .farmer-registration-container .card.active {
            transform: translateX(0);
        }

        .farmer-registration-container .card.previous {
            transform: translateX(-100%);
        }

        .farmer-registration-container .card h2 {
            margin-top: 0;
        }

        .farmer-registration-container .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .farmer-registration-container .next-btn,
        .farmer-registration-container .back-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .farmer-registration-container .next-btn {
            background-color: #007bff;
            color: #fff;
            align-self: flex-end;
            margin-left: auto;
        }

        .farmer-registration-container .next-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .farmer-registration-container .back-btn {
            background-color: #ccc;
            color: #333;
        }

        .farmer-registration-container .next-btn:hover,
        .farmer-registration-container .back-btn:hover {
            opacity: 0.9;
        }

        .farmer-registration-container .form-outline .form-control {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        .farmer-registration-container .form-outline .form-control:focus {
            border-color: #007bff;
            box-shadow: none;
        }

        .farmer-registration-container .form-inline .form-control {
            width: 100%;
        }
        .text-dager {
            font-size:0.875em;
        }
    </style>
</head>

<body>

    <?php include('../pages/includes/navbar.php'); ?>

    <div class="row">
        <?php include('../pages/includes/sidebar.php'); ?>

        <div class="col-md-9">
            <div class="farmer-registration-container">
                <div class="container">
                    <h1>Register Farmer Information</h1>
                    <div class="card-container">                 
                        <div class="card active" id="card1">
                            <h2>Personal Information</h2>
                            
                            <form id="form1" action="register_farmer.php" method="POST">
                                <div class="row mb-4">
                                    <div class="col">
                                        <div data-mdb-input-init class="form-inline">
                                        <label class="form-label" for="firstname">First Name</label>
                                            <input type="text" id="firstname" name="firstname" class="form-control" required />
                                            <div class="text-danger"> <?php echo $errors['firstname'] ?></div>
                                            
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div data-mdb-input-init class="form-inline">
                                        <label class="form-label" for="lastname">Last Name</label>
                                            <input type="text" id="lastname" name="lastname" class="form-control" required />
                                            <div class="text-danger"> <?php echo $errors['lastname'] ?></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div data-mdb-input-init class="form-inline mb-4">
                                <label class="form-label" for="phone">Phone</label>
                                    <input type="text" id="phone" name="phone" class="form-control" required />
                                    <div class="text-danger"> <?php echo $errors['phone'] ?></div>
                                    
                                </div>
                                <div data-mdb-input-init class="form-inline mb-4">
                                <label class="form-label" for="zanzibar_id">Zanzibar ID</label>
                                    <input type="text" id="zanzibar_id" name="zanzibar_id" class="form-control" required />
                                    <div class="text-danger"> <?php echo $errors['zanzibar_id'] ?></div>
                                    
                                </div>
                                
                                <div class="button-container">
                                    <button type="button" class="next-btn btn btn-primary" id="next1">Next</button>
                                </div>
                           
                        </div>
                        <div class="card" id="card2">
                            <h2>Address Information</h2>
                            
                                <div data-mdb-input-init class="form-inline mb-4">
                                    <label class="form-label" for="shehia">Shehia</label>
                                    <input type="text" id="shehia" name="shehia" class="form-control" required />
                                    
                                    <div class="text-danger"> <?php echo $errors['shehia'] ?></div>
                                </div>
                                <div data-mdb-input-init class="form-inline mb-4">
                                    <label class="form-label" for="wilaya">Wilaya</label>
                                    <input type="text" id="wilaya" name="wilaya" class="form-control" required />
                                    
                                    <div class="text-danger"> <?php echo $errors['wilaya'] ?></div>
                                </div>
                                <div data-mdb-input-init class="form-inline mb-4">
                                    <label class="form-label" for="amount_of_miche">Amount of Miche</label>
                                    <input type="number" id="amount_of_miche" name="amount_of_miche"  class="form-control" required />
                                    
                                    <div class="text-danger"> <?php echo $errors['amount_of_miche'] ?></div>
                                </div>
                                <div class="button-container">
                                    <button type="button" class="back-btn btn btn-secondary" id="back2">Back</button>
                                    <input type="submit" name="submit" class="next-btn btn btn-primary" value="Submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    <!-- Include MDB UI Kit JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.0.0/mdb.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card');
            const nextButtons = document.querySelectorAll('.next-btn');
            const backButtons = document.querySelectorAll('.back-btn');
            let currentCardIndex = 0;

            // Function to show the next card
            function showNextCard() {
                if (currentCardIndex < cards.length - 1) {
                    cards[currentCardIndex].classList.remove('active');
                    currentCardIndex++;
                    cards[currentCardIndex].classList.add('active');
                }
            }

            // Function to show the previous card
            function showPreviousCard() {
                if (currentCardIndex > 0) {
                    cards[currentCardIndex].classList.remove('active');
                    currentCardIndex--;
                    cards[currentCardIndex].classList.add('active');
                }
            }

            // Function to validate the form inputs
            function validateForm(form) {
                const inputs = form.querySelectorAll('input');
                let valid = true;
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        valid = false;
                    }
                });
                return valid;
            }

            // Attach event listeners to the next buttons
            nextButtons.forEach((button, index) => {
                button.addEventListener('click', () => {
                    const form = cards[currentCardIndex].querySelector('form');
                    if (validateForm(form)) {
                        showNextCard();
                    } else {
                        alert('Please fill in all required fields.');
                    }
                });
            });

            // Attach event listeners to the back buttons
            backButtons.forEach(button => {
                button.addEventListener('click', showPreviousCard);
            });
        });
    </script>
    
</body>

</html>
