<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Corrigiendo UTF-8 en descripcion_larga de Enlace Norte (ID=1)...\n\n";

// Correcciones para descripcion_larga
$correcciones = [
    1 => "Es un beneficio económico que entrega el Estado el cual asciende a $21.243 mensual, que el Estado entrega a niños, niñas y adolescentes menores de 18 años de edad. Pudiendo gestionar el trámite madres, padres o tutores que no cuenten con previsión social y que no cuentan con recursos suficientes para la mantención de sus cargas familiares (causantes de recibir el beneficio).",
    
    2 => "Es un beneficio económico que entrega el Estado destinado a mujeres embarazadas en situación de vulnerabilidad económica. El monto asciende a $21.243 mensuales desde el quinto mes de embarazo hasta el nacimiento del bebé. Este beneficio busca apoyar a las futuras madres que no cuentan con previsión social ni recursos suficientes para cubrir los gastos del embarazo.",
    
    3 => "Es un beneficio económico único de $73.000 que entrega el Estado para apoyar a las familias con un recién nacido. Se otorga por el nacimiento o adopción de un niño o niña, destinado a cubrir gastos básicos de los primeros meses de vida. Pueden solicitar este beneficio madres, padres o tutores que no cuenten con previsión social.",
    
    4 => "Es un beneficio económico mensual de $21.243 que entrega el Estado a madres en situación de vulnerabilidad que tienen hijos menores de 18 años. Este subsidio busca apoyar a las madres que no cuentan con previsión social ni recursos suficientes para la mantención de sus hijos.",
    
    8 => "Es un beneficio que reemplazó las pensiones del pilar solidario de vejez. La PGU beneficia a personas mayores de 65 años que pertenezcan al 90% más vulnerable. Se entrega independientemente de si la persona ya se ha pensionado o sigue trabajando.",
    
    9 => "Es un beneficio económico mensual dirigido a personas entre 18 y 65 años que presenten una discapacidad calificada como invalidez. El monto actual es de $126.521 mensuales. Este beneficio se otorga a quienes no reciben ninguna otra pensión y pertenecen al 60% más vulnerable de la población.",
    
    10 => "Es un complemento económico que se suma a la pensión de invalidez de aquellas personas que tienen una pensión contributiva baja. Está dirigido a personas entre 18 y 65 años con discapacidad que ya reciben una pensión, pero esta es insuficiente. El monto varía según los ingresos de la persona.",
    
    11 => "Es un beneficio económico adicional que se entrega a mujeres que reciben pensión (básica solidaria, de vejez o de invalidez) por cada hijo nacido vivo o adoptado. El monto es de aproximadamente $13.000 por hijo y se paga mensualmente junto con la pensión regular.",
    
    12 => "Es un beneficio económico mensual de $126.521 que entrega el Estado a personas con discapacidad mental en situación de vulnerabilidad. Está dirigido a personas que presenten discapacidad mental y pertenezcan a familias del 60% más vulnerable según el Registro Social de Hogares.",
    
    15 => "Es un subsidio que ayuda a pagar el consumo de agua potable y alcantarillado de las familias más vulnerables que viven en zonas urbanas. El beneficio cubre entre el 25% y el 40% del valor de la cuenta de agua, dependiendo de la situación socioeconómica de la familia.",
    
    16 => "Es un contrato mediante el cual el municipio cede de forma gratuita el uso de un bien mueble o inmueble municipal a una organización social o persona natural por un tiempo determinado. La organización se compromete a cuidar el bien y devolverlo en las mismas condiciones al término del contrato.",
    
    17 => "Las juntas de vecinos son organizaciones comunitarias de carácter territorial que representan a las personas que residen en un mismo barrio. La municipalidad brinda asesoría para la constitución, modificación de estatutos, renovación de directivas y actualización de personalidad jurídica de estas organizaciones.",
    
    19 => "Es la unidad municipal encargada de coordinar la respuesta ante emergencias y desastres en la comuna. Brinda apoyo logístico en situaciones de emergencia como incendios, inundaciones, temporales u otros eventos que afecten a la comunidad. También coordina la entrega de ayuda humanitaria a familias damnificadas."
];

$stmt = $pdo->prepare("UPDATE tramites SET descripcion_larga = ? WHERE id = ? AND departamento_id = 1");

$corregidos = 0;
foreach ($correcciones as $id => $texto) {
    $stmt->execute([$texto, $id]);
    if ($stmt->rowCount() > 0) {
        echo "✓ Corregido trámite ID $id\n";
        $corregidos++;
    }
}

echo "\n================================================================================\n";
echo "RESUMEN: Se corrigieron $corregidos descripciones largas en Enlace Norte\n";
echo "================================================================================\n";
