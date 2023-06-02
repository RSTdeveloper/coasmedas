<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RST Asociados</title>

    <link rel="icon" href="<?= base_url(); ?>/logoIcon.png" type="image/png" sizes="64x64" />

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/css/adminlte.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">


    <!-- /.login-box -->
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <!-- jQuery -->
    <script src="<?= base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?= base_url(); ?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <!--<script src="<?= base_url(); ?>/assets/dist/js/adminlte.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- MyStyle -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/css/mystyle.css">
    <!-- <link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/css/mystylePrueba.css"> -->

    <!-- MyStyle -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/css/login.css">

</head>
<body>
    <!--Hey! This is the original version of Simple CSS Waves-->
    <div class="prueba">
        <!--Content before waves-->
        <div class="wrapper">
            <div class="">
		        <div class="">
			        <div class="">
                        <img src="<?= base_url(); ?>/assets/dist/img/logo-coasmedas.png" class="logo" alt="RST Image">
                        <div class="title-text">
                            <div class="title login">Bienvenido</div>
                        </div>
                    </div>

			<div class="form-container">
				<div class="form-inner">                   					
						<form autocomplete="off" method="post" id="formIngresar" control="" class="login tex-ali">          
							    <input type="button" value="Iniciar Sesión" class="" onclick="login()">                                							
								<!-- <button type="button" onclick="login()"></button>               -->							
							<div class="field">
								<input autocomplete="off" type="number" name="cedula" id="cedula" class="" placeholder="Número de Documento" autocomplete="off">
							</div>
							<div class="field">
								<!-- <span class="fa fa-lock"></span> -->
								<input type="password" name="validar" id="validar" class="" placeholder="Ingrese codigo del mensaje" autocomplete="off">                
							</div>                        
						    </form> 
				        </div>
			        </div>
		        </div>
	        </div>
        </div>

        <!--Waves Container-->
        <!-- <div>
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
        viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
        <defs>
        <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
        </defs>
        <g class="parallax">
        <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
        <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
        <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
        <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
        </g>
        </svg>
        </div> -->
        <!--Waves end-->

    </div>
        <!--Header ends-->

        <!--Content starts-->
    <!-- <div class="content flex">
        <p class=" small float-md-center">RST Asociados Todos los derechos reservados.</p>      
    </div> -->
        <!--Content ends-->
	<!-- Main Content -->
	
	<!-- Footer -->
	<!-- <div class="container-fluid text-center footer">
        Hola mundo
	</div> -->
</body>
<footer class="sticky-footer" style="height: 0rem;">
    <div class="text-food">
        <strong>RST Asociados todos los derechos revervados.</strong>
    </div>
</footer>

    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        
        document.getElementById("cedula").addEventListener("keydown", function(e) {
            if (e.keyCode == 13) { //checks whether the pressed key is "Enter"
                login();
            }
        });

        document.getElementById("validar").addEventListener("keydown", function(e) {
            if (e.keyCode == 13) { //checks whether the pressed key is "Enter"
                login();
            }
        });

        async function login() {
            
            let identificacion= $('#cedula').val();
            let username= $('#validar').val();
            if(identificacion == '' && username ==''){
                Swal.fire({
                        icon: 'error',
                        title: 'Por favor ingrese los datos'
                    })
            }
            if(identificacion != '' && username ==''){
                Swal.fire({
                        icon: 'error',
                        title: 'Por favor ingrese el codigo enviado por mensaje de texto'
                    })
            }
            if(identificacion == '' && username !=''){
                Swal.fire({
                        icon: 'error',
                        title: 'Por favor ingrese el numero de documento registrado'
                    })
            }
            console.log(username)
            var data = {
                identificacion: $('#cedula').val(),
                username: $('#validar').val(),
                /*validation: captcha*/
            }
            $.post("<?= base_url(); ?>/login", data, function(res) {
                console.log('paso')
                if (res.type == 'error') {

                    Toast.fire({
                        icon: 'error',
                        title: res.message
                    })

                    return false;
                } else {
                    console.log(res);
                    window.location.href = '<?= base_url(); ?>/master'
                }

            }, 'JSON');
        }
    </script>





</html>