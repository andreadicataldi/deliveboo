<!DOCTYPE html>

<html>

<head>

    <title>Deliveboo checkout</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- for post req --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    {{-- for braintree functionality --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>

    {{-- style --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css"
        integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        small {
            line-height: 1.3;
        }

        .form-control::-webkit-input-placeholder {
            font-size: 0.8rem;
            color: red;
            opacity: .6;
        }

        .form-control {
            font-weight: 700;
        }

    </style>

</head>

<body>
    <div class="container-fluid bg-primary">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light ">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('img/logo_white.png') }}" alt="" height="45">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar_nav">
                        {{-- easter egg --}}
                        <a class="egg btn btn-outline-primary text-right">Sì, siamo pigri, ma ingegnosi...</a>

                        {{-- <li class="nav-item dropdown btn btn-light btn-sm">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Collabora con noi
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="admin">Ristoranti</a>
                                <a class="dropdown-item" href="#">Lavora con noi</a>
                                <a class="dropdown-item" href="#">Deliveboo per le Aziende</a>
                            </div>
                        </li>
                        <li class="nav-item btn btn-light btn-sm mx-1">
                            <a class="nav-link cart_button" id="cart_btn" href="#"><i
                                    class="fas fa-shopping-cart "></i><span class="cart_badge">2</span><span
                                    class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item btn btn-light btn-sm">
                            <a class="nav-link" href="#">Menu</a>
                        </li> --}}
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6" id="app">
                <div class="m-2 p-3 border border-primary rounded shadow">
                    <cart />
                </div>
            </div>
            <div class="col-md-6">
                <form action="" method="post" class="m-2 p-3 border border-primary rounded shadow">
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" name="customer_name" id="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="customer_email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone">Telefono:</label>
                        <input type="text" name="customer_telephone" id="telephone" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Indirizzo di consegna:</label>
                        <input type="text" name="customer_address" id="address" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="delivery_date">Data di consegna:</label>
                        <input type="date" name="delivery_date" id="delivery_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="delivery_time">Orario di consegna:</label>
                        <input type="time" name="delivery_time" id="delivery_time" class="form-control" step="900"
                            required>
                        <small class="d-block text-info">Pranzo: dalle 13:00 alle 15:00<br />Cena: dalle 18:00 alle
                            21:00</small>
                    </div>

                    <div class="form-group">
                        <button class="confirm btn btn-lg btn-primary btn-submit">Conferma i dati</button>
                        <span id="wait" class="d-none ml-2 spinner-grow spinner-grow-sm text-primary"></span>
                    </div>
                </form>
            </div>
        </div>


        {{-- braintree dropin --}}
        <div class="row" style="margin-bottom: 10vh">
            <div class="col-md-6 offset-md-6">
                <div class="m-2">
                    <div id="dropin-container"></div>
                    <button id="submit-button" class="d-none btn btn-lg btn-success">Effettua il pagamento</button>
                </div>
            </div>
        </div>

    </div>

</body>

<script>
    // easter egg
    let egg = document.querySelector('a.egg');
    egg.addEventListener('click', function() {
        $("input[name=customer_name]").val('andrea');
        $("input[name=customer_email]").val('andrea@example.com');
        $("input[name=customer_telephone]").val('312123231');
        $("input[name=customer_address]").val('Via del team 3');
        // $("input[name=delivery_time]").val('20:00');
    });


    // date and time inputs defaults
    document.getElementById('delivery_date').valueAsDate = new Date();
    // mirko.dir
    // let nowHour = new Date().getHours().toString();
    // console.log(nowHour)
    // let nowMinute = new Date().getMinutes().toString();
    // console.log(nowMinute)
    // let nowMinute = new Date() 

    // andrea.dir
    let nowTime = new Date().getHours();
    let lunch = 13;
    let dinner = 18;
    if (nowTime <= lunch) {
        document.getElementById('delivery_time').value = '13:00';
    } else if (nowTime > lunch && nowTime <= dinner) {
        //settare il valore input
        document.getElementById('delivery_time').value = '18:00';
    }

    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

    });

    $(".btn-submit").click(function(e) {

        e.preventDefault();
        // compare la scritta attendi
        document.getElementById('wait').classList.remove('d-none');
        document.getElementById('wait').classList.add('d-inline-block');

        var name = $("input[name=customer_name]").val();

        var email = $("input[name=customer_email]").val();

        var telephone = $("input[name=customer_telephone]").val();
        // console.log(telephone);

        var address = $("input[name=customer_address]").val();

        var delivery_date = $("input[name=delivery_date]").val();

        var delivery_time = $("input[name=delivery_time]").val();

        let carrellino = JSON.parse(localStorage.getItem('carrello'));
        let risto_id = 0;
        let tot = 0;
        if (localStorage.carrello) {
            carrellino.forEach(item => {
                let subtotal = item.qty * item.price;
                tot += subtotal;
                risto_id = item.risto_id;
            })
        }
        /* console.log(tot, risto_id); */

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({

            type: 'POST',

            url: '/ajaxRequest',

            data: {
                _token: CSRF_TOKEN,
                customer_name: name,
                customer_email: email,
                customer_telephone: telephone,
                customer_address: address,
                delivery_date: delivery_date,
                delivery_time: delivery_time,
                amount: tot,
                user_id: risto_id,
            },

            success: function(response) {
                // entra in gioco braintree
                braintree.dropin.create({
                    authorization: "{{ Braintree\ClientToken::generate() }}",
                    container: '#dropin-container'
                }, function(createErr, instance) {
                    // let carrellino = JSON.parse(localStorage.getItem('carrello'));
                    // let tot = 0;
                    // if (localStorage.carrello) {
                    //     carrellino.forEach(item => {
                    //         let subtotal = item.qty * item.price;
                    //         tot += subtotal;
                    //     })
                    // }
                    document.getElementById('wait').classList.add('d-none');
                    document.getElementById('wait').classList.remove('d-inline-block');
                    window.scrollTo(0, document.body.scrollHeight);
                    document.querySelector('button.confirm').disabled = true;
                    var button = document.getElementById('submit-button');
                    button.classList.remove('d-none');

                    button.addEventListener('click', function() {
                        instance.requestPaymentMethod(function(err, payload) {
                            payload.tot = tot;
                            $.get('{{ route('payment.process') }}', {
                                    payload
                                },
                                function(response) {
                                    console.log(response);
                                    if (response.success) {
                                        alert('Payment successful!');
                                        return window.location.replace(
                                            "http://127.0.0.1:8000");
                                    } else {
                                        alert('Payment failed');
                                    }
                                }, 'json');
                        });
                    });
                });

            },

            error: function(err) {

                document.getElementById('wait').classList.add('d-none');
                document.getElementById('wait').classList.remove('d-inline-block');

                // logica errori
                let errorsObj = err.responseJSON.errors;
                console.log(errorsObj);

                for (const key in errorsObj) {
                    let formInputs = document.querySelectorAll('form input');
                    formInputs.forEach(input => {
                        if (key == input.name) {
                            input.placeholder = '* Campo obbligatorio'
                        }
                    });
                }




                // const errors = err.responseJSON.errors
                // console.dir(err.responseJSON.errors);
                // console.log(key);
                // for (const key in errors) {
                //     if (errors.hasOwnProperty.call(errors, key)) {
                //         const element = errors[key];
                //         let errorMsg = element.toString();
                //         let i = document.getElementsByName(`${key}`);
                //         let catchError = i[0].getAttribute('name');
                //         if (key == catchError) {
                //             i[0].style.border = '1px solid red';
                //             i[0].classList.add('is-invalid');
                //         }
                //     }
                // }
            }
        });
    });

</script>
<script src="{{ asset('js/app.js') }}" defer></script>

</html>
