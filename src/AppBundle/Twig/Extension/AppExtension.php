<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12/14/2017
 * Time: 9:26 AM
 */
namespace AppBundle\Twig\Extension;
class AppExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'app_extension';
    }

    public function separarCaracteres($texto) {
        //$pregunta = $this->em->getRepository('AppBundle:Pregunta')->find($id);
        return str_split($texto);
    }
    public function getFunctions()
    {
        return array(
            'separarCaracteres' => new \Twig_Function_Method($this, 'separarCaracteres'),
        );
    }

    public function getFilters()
    {
        return array(
// ...

            //Pone tiempo que le queda el estudiante para resolver la prueba
            'cuenta_atras' => new \Twig_Filter_Method($this, 'cuentaAtras',
                array('is_safe' => array('html'))),
            //Muestra en lista de examen la fecha o el tiempo que queda o el boton resolver o pasado
            'mostrar_tiempo' => new \Twig_Filter_Method($this, 'mostrarTiempo',
                array('is_safe' => array('html'))),
            //Muestra hora actual
            'hora_actual' => new \Twig_Filter_Method($this, 'horaActual',
                array('is_safe' => array('html'))),
        );
    }

    public function horaActual($fecha, $zonahoraria)
    {
        $fecha = new \DateTime('now');
        $fecha -> setTimezone(new \DateTimeZone($zonahoraria));


        $fecha = $fecha->format('Y,')
            .($fecha->format('m,'))
            .$fecha->format('d,')
            .($fecha->format('H,'))
            .($fecha->format('i'))
            .$fecha->format(',s');

        $html = <<<EOJ
<script type="text/javascript">


var horaservidorca = new Date($fecha);
var ahoras = horaservidorca.getHours();
var aminutos = horaservidorca.getMinutes();
var asegundos = horaservidorca.getSeconds();

var horaclienteca = new Date();
var as31 = horaclienteca.getSeconds();
var as32 = horaclienteca.getSeconds();
var aresult3 = 0;

var diferenciaserclica = horaservidorca.getTime()-horaclienteca.getTime();
var newtimehoraca = horaclienteca.getTime()+diferenciaserclica;
var newhoraca = new Date(newtimehoraca);


var cerohora='';
var cerominuto='';
var cerosegundo='';



function muestraHora(){
setTimeout('muestraHora()', 1000);
//ahorat1 = new Date();

cerohora='';
cerominuto='';
cerosegundo='';

as31 = as32;
horaclienteca = new Date();
as32 = horaclienteca.getSeconds();
aresult3 = as32-as31;

if(as31!=as32){
newtimehoraca = horaclienteca.getTime()+diferenciaserclica;
newhoraca = new Date(newtimehoraca);

ahoras = newhoraca.getHours();
aminutos = newhoraca.getMinutes();
asegundos = newhoraca.getSeconds();
}


if(ahoras< 10){
cerohora='0';
}
if(aminutos< 10){
cerominuto='0';
}
if(asegundos< 10){
cerosegundo='0';
}

document.getElementById('tiempo').innerHTML = '<strong>'+cerohora+''+ahoras+':'+cerominuto+''+aminutos +':'+ cerosegundo+''+ asegundos +'</strong> ';


}
muestraHora();




</script>
EOJ;
        return $html;
    }

    public function cuentaAtras($fechax, $minutos, $deprueba, $zonahoraria)
    {
        if($deprueba){
            $fecha = new \DateTime();
            $fecha -> setTimezone(new \DateTimeZone($zonahoraria));
        }else{

            $fecha = (\DateTime::createFromFormat('Y-m-d-H-i', $fechax));
        }
        //$fecha = $fecha->format("Y-m-d-H-i");
        $fecha = $fecha->format('Y,')
            .($fecha->format('m'))
            .$fecha->format(',d,')
            .($fecha->format('H,'))
            .($fecha->format('i')+$minutos)
            .$fecha->format(',s');

        $fechaa = new \DateTime();
        $fechaa -> setTimezone(new \DateTimeZone($zonahoraria));

        //$fecha = $fecha->format("Y-m-d-H-i");
        $fechaa = $fechaa->format('Y,')
            .($fechaa->format('m'))
            .$fechaa->format(',d,')
            .($fechaa->format('H,'))
            .($fechaa->format('i'))
            .$fechaa->format(',s');

        //$fecha = $fecha->format("d-m-Y");

        $html = <<<EOJ
<script type="text/javascript">
var ahorater = new Date($fechaa);
var fechaExpiracionter = new Date($fecha);

var dter = new Date();
var faltaeter = Math.floor( (fechaExpiracionter.getTime() - ahorater.getTime()) / 1000 );
var diffaeter =  ahorater.getTime() - dter.getTime();

var resultter = 0;
function muestraCuentaAtras(){
setTimeout('muestraCuentaAtras()', 1000);
var horaster, minutoster, segundoster;

//s31 = s32;
dter = new Date();
dter = new Date(dter.getTime()+ diffaeter );

horaster = (fechaExpiracionter.getHours() - dter.getHours());
minutoster = (fechaExpiracionter.getMinutes() - dter.getMinutes());
segundoster = (fechaExpiracionter.getSeconds() - dter.getSeconds());

if (horaster < 0 || minutoster<0) {
    cuentaAtraster = '-';
}else
if (horaster <= 0 && minutoster<=0 && segundoster<=0) {
    cuentaAtraster = '-';
}else {
if(segundoster<0){
    segundoster = segundoster + 60;
    minutoster--;
    if(minutoster<0){
        minutoster = minutoster+60;
        horaster--;
    }
}
cuentaAtraster = (horaster < 10 ? '0' + horaster : horaster) + 'h '
+ (minutoster < 10 ? '0' + minutoster : minutoster) + 'm '
+ (segundoster < 10 ? '0' + segundoster : segundoster) + 's';
}

tiempoEnvio(horaster, minutoster, segundoster);
document.getElementById('tiempo').innerHTML = '<strong>Faltan:</strong> ' + cuentaAtraster;
}
muestraCuentaAtras();
function tiempoEnvio(horas, minutos, segundos){
if(horas==0 & minutos ==0 & segundos < 1){
jQuery('#form_send').click();
}
}
</script>
EOJ;
        return $html;
    }

    public function mostrarTiempo($fecha, $minutos, $ruta, $indice, $zonahoraria)
    {

        $fecha = (\DateTime::createFromFormat('Y-m-d-H-i', $fecha));

        //$fecha = $fecha->format("Y-m-d-H-i");
        $fecha = $fecha->format('Y,')
            .($fecha->format('m'))
            .$fecha->format(',d,')
            .($fecha->format('H,'))
            .($fecha->format('i'))
            .$fecha->format(',s');

        $fechaa = new \DateTime();
        $fechaa -> setTimezone(new \DateTimeZone($zonahoraria));

        //$fecha = $fecha->format("Y-m-d-H-i");
        $fechaa = $fechaa->format('Y,')
            .($fechaa->format('m'))
            .$fechaa->format(',d,')
            .($fechaa->format('H,'))
            .($fechaa->format('i'))
            .$fechaa->format(',s');

        //$fecha = $fecha->format("d-m-Y");

        $html = <<<EOJ
<script type="text/javascript">

var ahora$indice = new Date($fechaa);
var fechaExpiracion$indice = new Date($fecha);
var falta$indice = Math.floor( (fechaExpiracion$indice.getTime() - ahora$indice.getTime()) /
1000 );
var difflink$indice = (new Date()).getTime() - (ahora$indice).getTime();
var falta$indice = Math.floor( (fechaExpiracion$indice.getTime() - (new Date()).getTime()) /
1000 );
var queda$indice = falta$indice;

var th$indice = new Date();
var sh1$indice = th$indice.getSeconds();
var sh2$indice = th$indice.getSeconds();
var resulth$indice = 0;


function muestraCuentaAtras$indice(){
setTimeout('muestraCuentaAtras$indice()', 1000);
var horas, minutos, segundos;

sh1$indice = sh2$indice;
th3$indice = new Date();
sh2$indice = th3$indice.getSeconds();
resulth$indice = sh2$indice - sh1$indice;


if(resulth$indice<0){
    resulth$indice = resulth$indice + 60;
}
if(sh1$indice!=sh2$indice){
   queda$indice = Math.floor( (fechaExpiracion$indice.getTime() - th3$indice.getTime()+difflink$indice) / 1000 );
   //queda$indice = queda$indice - resulth$indice;
}
falta$indice = queda$indice;

if (falta$indice > (60*60)) {
document.getElementById('tiempo$indice').innerHTML = 'Fecha examen '+fechaExpiracion$indice.getDate()+'-'+(fechaExpiracion$indice.getMonth())+'-'+fechaExpiracion$indice.getFullYear()+' '+fechaExpiracion$indice.getHours()+':'+fechaExpiracion$indice.getMinutes();

}else if (falta$indice > 0 && falta$indice < (60*60)) {
horas = Math.floor(falta$indice/3600);
falta$indice = falta$indice % 3600;
minutos = Math.floor(falta$indice/60);
falta$indice = falta$indice % 60;
segundos = Math.floor(falta$indice);
cuentaAtras =  (horas < 10 ? '0' + horas : horas) + 'h '
+ (minutos < 10 ? '0' + minutos : minutos) + 'm '
+ (segundos < 10 ? '0' + segundos : segundos) + 's ';
document.getElementById('tiempo$indice').innerHTML = cuentaAtras+' para examen';

}else if (falta$indice > -1*$minutos*60) {
document.getElementById('tiempo$indice').innerHTML = '<a href="$ruta">Resolver</a>';

}else if (falta$indice < -1*$minutos*60) {
cuentaAtras = 'Pasado de tiempo';
document.getElementById('tiempo$indice').innerHTML = cuentaAtras;
}

}
muestraCuentaAtras$indice();
</script>
EOJ;
        return $html;
    }

}