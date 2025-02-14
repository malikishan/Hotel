<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php');?>
    <title><?php echo $setting_r['site_title']?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">



    <style>
    .availability-form {
        margin-top: -50px;
        position: relative;
        z-index: 2;
    }

    @media screen and (max-width:575px) {
        .availability-form {
            margin-top: 25px;
            padding: 0 35px;
        }
    }

    #chat {
        font-size: 50px;
        position: fixed;
        color: #2ec1ac;
        left: 95%;
        z-index: 3;
        top: 80%;
        cursor: pointer;
    }

    .h6 {
        margin-left: 10px;
        padding-top: 0px;
        cursor: pointer;
    }

    .box {}
    </style>

</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>
    <div id="chat"><i class="bi bi-robot"></i>
        <h6 class="h6">24/7</h6>
    </div>
    <div class="box">
        <div id="wrapper">
            <div class="title">Atithi</div>
            <div class="form">
                <div class="bot-inbox inbox">
                    <div class="icon">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="msg-header">
                        <p>Hello there,how can I help you?</p>
                    </div>
                </div>
            </div>
            <div class="typing-field">
                <div class="input-data">
                    <input id="data" type="text" placeholder="Type something here.." required>
                    <button id="send-btn">Send</button>
                </div>
            </div>
        </div>
    </div>

    <!-- carousel -->
    <div class="container-fluid">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
       $res = selectAll('carousel');
       while($row = mysqli_fetch_assoc($res)){
        $path = CAROUSEL_IMG_PATH;
        echo <<<data
      
         <div class="swiper-slide">
         <img src="$path$row[image]"class="w-100 d-bolck" />
        </div>
        data;
    }
      
      ?>
            </div>

        </div>
    </div>

    <!--check avilability form  -->

    <div class="container availability-form">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h5 class="mb-4"> check avilability form</h5>
                <form>
                    <div class="row align-item-end">
                        <div class="col-lg-3 mb-4">
                            <label class="form-label " style="font-weight:500">check-in</label>
                            <input type="date" class="form-control shadow-none">
                        </div>

                        <div class="col-lg-3 mb-4">
                            <label class="form-label " style="font-weight:500">check-out</label>
                            <input type="date" class="form-control shadow-none">
                        </div>

                        <div class="col-lg-3 mb-4">
                            <label class="form-label " style="font-weight:500">Adult</label>
                            <select class="form-select form-select-sm">
                                <option selected>Open this select menu</option>
                                <option value="1">one</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-4">
                            <label class="form-label " style="font-weight:500">children</label>
                            <select class="form-select form-select-sm">
                                <option selected>Open this select menu</option>
                                <option value="1">one</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="col-lg-1 mb-lg-3 mt-2">
                            <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br><br><br>



    <!--Our Rooms  -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our Rooms</h2>
    <div class="container">
        <div class="row">

            <?php
              
              $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC  LIMIT 3",[1,0],'ii');
              while($room_data = mysqli_fetch_assoc($room_res))
              {



                $fea_q = mysqli_query($conn,"SELECT f.name FROM `features` f 
                INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
                WHERE rfea.room_id ='$room_data[id]'");
                
                
                $features_data="";
                while($fea_row = mysqli_fetch_assoc($fea_q))
                {
                    $features_data .=" <span class='badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base'>
                                    $fea_row[name]
                            
                                </span>";
                      
                            }
                        
                           // facilities 

                           
                $fac_q = mysqli_query($conn,"SELECT f.name FROM `facilities` f 
                INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
                WHERE rfac.room_id ='$room_data[id]'");
                
                
                $facilities_data="";
                while($fac_row = mysqli_fetch_assoc($fac_q))
                {
                    $facilities_data .=" <span class='badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base'>
                                    $fac_row[name]
                            
                                </span>";
           
                            }

                            //get thumbnail
                            
                    $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
                    $thumb_q =  mysqli_query($conn,"SELECT * FROM `room_images` 
                    WHERE `room_id`='$room_data[id]' 
                    AND `thumb`='0'");

                    if(mysqli_num_rows($thumb_q)>0)
                    {
                        $thumb_res=mysqli_fetch_assoc($thumb_q);
                        $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
                    }

                    $book_btn ="";
                    if(!$setting_r['shutdown']){
                        $login=0;
                    if(isset($_SESSION['login']) && $_SESSION['login']==true)
                    {
                        $login=1;
                    }
                        $book_btn =  "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm text-white custom-bg shadow-none'>Book Now</button>";
                    }


                    echo<<<data
            

                       <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow" style="max-width: 350px; margin:auto;">
                          <img src="$room_thumb" class="card-img-top" alt="...">
                              <div class="card-body">
                                <h5>$room_data[name]</h5>
                                <h6 class="mb-4">₹$room_data[price] for 4 hour</h6>
                              <div class="features mb-4">
                                <h6 class="mb-1">Features</h6>
                                $features_data
                            </div>
                            <div class="facilities mb-4">
                                <h6 class="mb-1">Facilities</h6>

                              $facilities_data
                            </div>
                            <div class="guests mb-4">
                                <h6 class="mb-1">Guests</h6>
                                <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
                                      $room_data[adult] adults
                                </span>
                                <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
                                    $room_data[children] children
                                </span>
                            </div>
                            <div class="rating mb-4">
                                <h6 class="mb-1">Rating</h6>
                                <span class="badge rouded-pill bg-light">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </span>
                            </div>
                            <div class="d-flex justify-content-evenly mb-2">

                                 $book_btn 
                                <a href="room_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none">More deatils</a>

                                      </div>
                              </div>
                          </div>
                      </div>

                    data;
              }
              
         ?>

            <div class="col-lg-12 text-center mt-5">
                <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Rooms >>></a>
            </div>
        </div>
    </div>


    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our FACILITIES</h2>
    <div class="container">
        <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
            <?php
      $res = mysqli_query($conn,"SELECT * FROM `facilities`ORDER BY `id` DESC LIMIT 5");
      $path = FACILITIES_IMG_PATH;


      while($row = mysqli_fetch_assoc($res)){
      
      echo<<<data
                <div class="col-lg-2 col-md-2 text-center bg-wight rounded shadow py-4 my-3">
                <img src="$path$row[icon]" width="80px">
                <h5 class="mt-3">$row[name]</h5>
              </div>
           
          data;

    }
    ?>

            <div class="col-lg-12 text-center mt-5">
                <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More
                    Facilities >>></a>

            </div>
        </div>
    </div>

    <!--testimonials-->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TESTMONIALS</h2>
    <div class="container mt-5">
        <div class="swiper swiper-testmonials">
            <div class="swiper-wrapper mb-5">
                <div class="swiper-slide bg-white p-4">
                    <div class="portfile d-flex align-items-center mb-3">
                        <!-- <img src="" hight="30px"> -->
                        <i class="bi bi-star-fill "></i>

                        <h6 class="m-0 ms-2">Random user1</h6>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Aperiam eveniet ut dignissimos sequi nihil accusamus,
                        eaque maiores obcaecati.
                    </p>
                    <div class="rating">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>

                <div class="swiper-slide bg-white p-4">
                    <div class="portfile d-flex align-items-center mb-3">
                        <!-- <img src="" hight="30px"> -->
                        <i class="bi bi-star-fill "></i>

                        <h6 class="m-0 ms-2">Random user</h6>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Aperiam eveniet ut dignissimos sequi nihil accusamus,
                        eaque maiores obcaecati.
                    </p>
                    <div class="rating">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>

                <div class="swiper-slide bg-white p-4">
                    <div class="portfile d-flex align-items-center mb-3">
                        <!-- <img src="" hight="30px"> -->
                        <i class="bi bi-star-fill "></i>

                        <h6 class="m-0 ms-2">Random user</h6>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Aperiam eveniet ut dignissimos sequi nihil accusamus,
                        eaque maiores obcaecati.
                    </p>
                    <div class="rating">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <div class="col-lg-12 text-center mt-5">
        <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know More>>></a>

    </div>


    <!-- reach us -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Reach US</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe']?>"
                    loading="lazy"></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Call us</h5>
                    <a href="tel: +919998886644" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1']?></a><br>

                    <?php 
        if($contact_r['pn2']!='')
        {
          echo<<<data
          <a href="tel: +$contact_r[pn2]"class="d-inline-block mb-2 text-decoration-none text-dark">
          <i class="bi bi-telephone-fill"></i>  +$contact_r[pn2]</a>
          data;
        }
        
        ?>
                </div>
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Follow us</h5>
                    <?php
        if($contact_r['tw']!=''){
         
         echo<<<data
          <a href="$contact_r[tw]"class="d-inline-block mb-2">
          <span class="badge bg-light text-dark fs-6 p-2"><i class="bi bi-twitter-x me-1"></i>Twitter</span></a><br>
        data;
          }
        ?>

                    <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-2">
                        <span class="badge bg-light text-dark fs-6 p-2"><i
                                class="bi bi-facebook me-1"></i>Facebook</span></a><br>
                    <a href="<?php echo $contact_r['inst'] ?>" class="d-inline-block mb-2">
                        <span class="badge bg-light text-dark fs-6 p-2"><i
                                class="bi bi-instagram me-1"></i>Instagram</span></a><br>

                </div>
            </div>
        </div>
    </div>
    </div>
    <?php require('inc/footer.php');?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    var swiper = new Swiper(".swiper-container", {
        spaceBetween: 30,
        effect: "fade",
        loop: true,
        autoplay: {
            delay: 3500,
            diasableInteration: false,
        }

    });
    var swiper = new Swiper(".swiper-testmonials", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        slidesPerView: "3",
        loop: true,
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: false,
        },
        pagination: {
            el: ".swiper-pagination",
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            640: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },

            1024: {
                slidesPerView: 3,
            },
        }
    });

    $(document).ready(function() {
        $("#send-btn").on("click", function() {
            $value = $("#data").val();
            $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>' + $value +
                '</p></div></div>';
            $(".form").append($msg);
            $("#data").val('');

            // start ajax code
            $.ajax({
                url: 'message.php',
                type: 'POST',
                data: 'text=' + $value,
                success: function(result) {
                    $replay =
                        '<div class="bot-inbox inbox"><div class="icon"><i class="fas fa-user"></i></div><div class="msg-header"><p>' +
                        result + '</p></div></div>';
                    $(".form").append($replay);
                    // when chat goes down the scroll bar automatically comes to the bottom
                    $(".form").scrollTop($(".form")[0].scrollHeight);
                }
            });
        });
    });

    document.getElementById("chat").addEventListener("click", myFunction);
    var flg = 0;

    function myFunction() {
        if (flg == 0) {

            document.getElementById("wrapper").style.opacity = "100";
            flg = 1;
        } else {
            document.getElementById("wrapper").style.opacity = "0";
            flg = 0;

        }
    }
    </script>
</body>

</html>