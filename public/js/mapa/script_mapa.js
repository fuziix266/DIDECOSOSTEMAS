var osmMap;
var tileLayer;
var mapboxSatelliteLayer;
var userMarker;
var destinationMarker;

// Funciones de Pantalla de Carga
function mostrarPantallaCarga() {
    if (!$('#pantallaCarga').hasClass('show')) {
        $("#pantallaCarga").modal('show');
    }
}

function ocultarPantallaCarga() {
    $("#pantallaCarga").modal('hide');
}

// Geolocalización
function handleLocationError(error) {
    console.error("Error de geolocalización: " + error.message);
    ocultarPantallaCarga();
}

async function miUbicacion() {
    return new Promise((resolve, reject) => {
        mostrarPantallaCarga();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    updateLocation(position);
                    resolve(position);
                },
                (error) => {
                    handleLocationError(error);
                    reject(error);
                },
                {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 60000
                }
            );
        } else {
            const error = new Error("Geolocalización no soportada por este navegador.");
            alert(error.message);
            ocultarPantallaCarga();
            reject(error);
        }
    });
}

function updateLocation(position) {
    var lat = position.coords.latitude;
    var lon = position.coords.longitude;

    if (userMarker) {
        osmMap.removeLayer(userMarker);
    }

    userMarker = L.marker([lat, lon], {
        icon: L.icon({
            iconUrl: '/didecosistemas/public/img/marcadores-mapa/marker-icon-green.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            className: 'miUbicacion'
        })
    }).addTo(osmMap);

    osmMap.setView([lat, lon], 18);
    ocultarPantallaCarga();
}

// Datos de Unidades Municipales
// Coordinates synced with ubicaciones.md
let selectedUnidad = null;
let currentMarker = null;

const unidadesMunicipales = [
    {
        "nombre": "Administración Municipal",
        "coords": [-18.4799125, -70.3209914],
        "descripcion": "La Administración Municipal dependerá del Alcalde y tiene por objetivo administrar la organización interna de la Municipalidad, de acuerdo a los planes y programas establecidos, a las atribuciones que le señale el presente Reglamento Municipal y a las que le delegue la máxima autoridad.",
        "director": "Marcelo Cañipa Zegarra",
        "ubicacion": "Rafael Sotomayor 415",
        "correo": [
            "rosa.perez@municipalidadarica.cl",
            "silvia.friz@municipalidadarica.cl"
        ],
        "telefono": [
            "432380249",
            "432380272"
        ],
        "funciones": [
            "A) Elaborar el Plan Anual de Acción Municipal en conjunto con el Alcalde, en el marco de la estrategia municipal y ejerciendo las funciones de seguimiento y control para el cumplimiento de sus objetivos y evaluación de resultados.",
            "B) Ejecutar las tareas de dirección y coordinación permanente de todas las unidades municipales y servicio municipalizados de acuerdo a las acciones determinadas en el Plan de Acción Municipal y las funciones del presente Reglamento.",
            "C) Adoptar las providencias necesarias para el adecuado cumplimiento de la gestión y ejecución técnica de las políticas, planes, programas y proyectos municipales, que se relacionen con la gestión municipal.",
            "D) Asesorar al Alcalde en las diversas materias municipales, que permitan tomar decisiones conducentes al logro de los objetivos y metas propuestas.",
            "E) Cautelar la permanente coordinación entre las Direcciones adscritas a las respectivas áreas estratégicas y evaluar el cumplimiento de los programas y actividades establecidas.",
            "F) Asesorar al Alcalde, en conjunto con la Dirección de Administración y Finanzas, en la administración y gestión de los Recursos Humanos de la Municipalidad.",
            "G) Elaborar, proponer y programar en coordinación con otras unidades municipales, los gastos de inversión en el municipio, necesarios para la adecuada gestión y gastos de operación.",
            "H) Dirigir y supervisar el quehacer de las direcciones y departamentos en función de los planes e instrumentos rectores del municipio (Plan Anual, Pladeco, Presupuesto municipal y la planificación estratégica).",
            "I) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo.",
            "J) Cumplir otras funciones que el Alcalde le encomiende, en conformidad al artículo 63 de la Ley 18.695, exceptuando la letra c) y d)."
        ],
        "link": ""
    },
    {
        "nombre": "Administración y Finanzas",
        "coords": [-18.4785, -70.3182],
        "descripcion": "La Dirección de Administración y Finanzas, dependerá jerárquicamente de la Administración Municipal y tendrá como función procurar la óptima provisión, asignación y utilización de los recursos humanos, económicos y materiales necesarios para el funcionamiento municipal, de acuerdo a lo dispuesto en el Art. 27 de la Ley.",
        "director": "Mauricio Albanes Gomez (S)",
        "ubicacion": "Bernardo O'higgins # 749",
        "correo": [
            "paula.orellana@municipalidadarica.cl"
        ],
        "telefono": [],
        "funciones": [
            "A) Asesorar al Alcalde en la administración del personal de la Municipalidad.",
            "B) Asesorar al Alcalde en la administración financiera de los bienes municipales, para lo cual le corresponderá específicamente:",
            "-  Estudiar, calcular, proponer y regular la percepción de cualquier tipo de ingresos municipales.",
            "-  Colaborar con la Secretaría Comunal de Planificación y coordinación en la elaboración del Presupuesto Municipal.",
            "-  Visar los Decretos de Pago.",
            "-  Llevar la contabilidad municipal en conformidad con",
            "las normas de la contabilidad nacional y con las instrucciones que al respecto imparta la Contraloría General de la República",
            "-  Controlar la gestión financiera de las empresas municipales.",
            "-  Efectuar los pagos municipales, manejar las cuentas bancarias respectivas y rendir cuenta a la Contraloría General de la República.",
            "-  Recaudar y percibir los ingresos municipales y fiscales que corresponda.",
            "C) Informar trimestralmente al Concejo sobre el detalle mensual de los pasivos acumulados, desglosando las cuentas por pagar por el municipio y las corporaciones municipales. Al efecto, dichas corporaciones deberán informar a esta unidad acerca de su situación financiera, desglosando las cuentas por pagar.",
            "D) Mantener un registro mensual, el que estará disponible para conocimiento público, sobre el desglose de los gastos del municipio. En todo caso, cada concejal tendrá acceso permanente a todos los gastos efectuados por la Municipalidad.",
            "E) El Informe trimestral y el registro mensual a que se refieren las letras c) y d) deberán estar disponibles en la página Web del municipio.",
            "F) Medir la eficiencia en las labores del personal de su unidad, entregarles formación para optimizar su desempeño, velar por su salud y seguridad y gestionar estrategias de desarrollo que permitan llevar a cabo los objetivos estratégicos del municipio.",
            "G) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo.",
            "H) Prestar apoyo administrativo al Alcalde y a las distintas unidades municipales."
        ],
        "link": ""
    },
    {
        "nombre": "Asesoría Jurídica",
        "coords": [-18.4802, -70.3207],
        "descripcion": "La Asesoría Jurídica dependerá directamente del Alcalde(sa) y de conformidad a la Ley Orgánica Constitucional de Municipalidades, las principales funciones de la Unidad serán asesorar jurídicamente al Alcalde y al Concejo Municipal, informar en derecho las consultas que planteen las distintas unidades municipales, llevar el registro de los bienes municipales, asumir la defensa judicial que el Alcalde(sa) en todos aquello juicios en que la Municipalidad sea parte o tenga interés, o prestar asesoría o defensa de la comunidad cuando sea procedente y el alcalde(sa) así lo determine y efectuar los procedimientos disciplinarios que ordene el Alcalde, ejerciendo la supervigilancia de aquellos que lleven los funcionarios de cualquier unidad municipal.",
        "director": "Javiera Flores Kesler",
        "ubicacion": "Rafael Sotomayor # 415",
        "correo": [
            "katherinne.vicencio@municipalidadarica.cl"
        ],
        "telefono": [
            "432380212"
        ],
        "funciones": [
            "A) Prestar asesoría jurídica al Alcalde y al Concejo Municipal.",
            "B) Informar en Derecho los planteamientos sometidos a su conocimiento, previo informe de la Unidad Municipal requirente, la que, además, deberá acompañar los antecedentes particulares del caso para su mejor examen.",
            "C) Orientar periódicamente respecto de las disposiciones legales y reglamentarias vigentes.",
            "D) Mantener al día los títulos de los Bienes Raíces municipales.",
            "E) Iniciar y asumir la defensa, o requerimiento del Alcalde(sa), en todos aquellos juicios, en que la municipalidad sea parte o tenga interés, pudiendo corresponderle también la asesoría o defensa de la comunidad cuando sea procedente y el Alcalde(sa) así lo determine.",
            "F) Sustanciar los procedimientos disciplinarios que se asignen a los funcionarios de la Unidad y ejercer la supervigilancia de aquellos sustanciados por funcionarios de las distintas unidades municipales.",
            "G) Otras funciones que la Ley señale o que la Autoridad superior le asigne."
        ],
        "link": ""
    },
    {
        "nombre": "Cementerios",
        "coords": [-18.4862, -70.3090],
        "descripcion": "El Departamento Municipal de Cementerios, dependiente de la Administración Municipal, tendrá como función administrar los Cementerios de Arica y San Miguel de Azapa. Dispondrá de la figura de un Administrador en cada uno de ellos.",
        "director": "Alexis Navarro Núñez",
        "ubicacion": "Lastarria 1001 (En la entrada del cementerio municipal de Arica)",
        "correo": [
            "alexis.navarro@municipalidadarica.cl"
        ],
        "telefono": [
            "432380662",
            "432380661"
        ],
        "funciones": [
            "A) Administrar los cementerios de Arica y San Miguel de Azapa.",
            "B) Recepcionar y resguardar los ingresos que se generan en los cementerios.",
            "C) Efectuar la venta de nichos.",
            "D) Llevar registros de las inhumaciones y exhumaciones.",
            "E) Informar mensualmente a la Autoridad, de las inhumaciones y exhumaciones.",
            "F) Verificar stock de nichos e informar oportunamente de las necesidades de construcción.",
            "G) Administrar los servicios de mantención y aseo de los recintos.",
            "H) Efectuar la mantención de los recintos.",
            "I) Efectuar requerimiento de inscripción de defunción en el Registro Civil."
        ],
        "link": ""
    },
    {
        "nombre": "Control",
        "coords": [-18.4802, -70.3207],
        "descripcion": "La Dirección de Control será la encargada de fiscalizar la gestión del municipio en lo que concierne a la legalidad de sus actos, en el marco de los procesos administrativos establecidos y la normativa vigente. La Dirección de Control, dependerá del Alcalde y de acuerdo a lo establecido en el artículo 29 de la Ley 18.695.",
        "director": "Arturo Butrón Choque",
        "ubicacion": "Rafael Sotomayor 415",
        "correo": [],
        "telefono": [
            "432380222"
        ],
        "funciones": [
            "A) Realizar la auditoría operativa interna de la Municipalidad y de los servicios - traspasados, con el objeto de fiscalizar la legalidad de su actuación. Esta función se realizará a partir de la ejecución de un plan de fiscalización anual y de la aplicación de los procedimientos regulados y establecidos en los instrumentos normativos del municipio.",
            "B) Controlar la ejecución financiera y presupuestaria de la gestión municipal, aplicando las normas de auditoría operativa vigente y los procedimientos establecidos en los instrumentos normativos.",
            "C) Representar al Alcalde en los actos municipales cuando estime ilegales, informando de ello al Concejo, para cuyo objeto tendrá acceso a toda la información disponible. Dicha representación deberá efectuarse dentro de los diez días siguientes a aquel en que la unidad de control haya tomado conocimiento de los actos;",
            "D) Colaborar directamente con el Concejo Municipal para el ejercicio de sus funciones fiscalizadoras. Para estos efectos, emitirá un informe trimestral acerca del estado de avance del ejercicio programático presupuestario; asimismo, deberá informar, también trimestralmente, sobre el estado de cumplimiento de los pagos por concepto de cotizaciones provisionales de los funcionarios municipales y de los trabajadores que se desempeñan en servicios incorporados a la gestión municipal, administrados directamente por la municipalidad o a través de corporaciones municipales, y de los aportes que la municipalidad debe efectuar al Fondo Común Municipal, y del estado de cumplimiento de los pagos por concepto de asignaciones de perfeccionamiento docente. En todo caso, deberá dar respuesta por escrito a las consultas o peticiones de informes que le formule un concejal.",
            "E) Asesorar en la definición y evaluación de la auditoría externa que el Concejo pueda requerir en virtud de la ley 18.695.",
            "F) Realizar con la periodicidad que determine el reglamento, una presentación en sesión de comisión del Concejo destinada a que sus miembros puedan formular consultas referidas al cumplimiento de las funciones que a éste le competen.",
            "G) Fiscalizar las corporaciones, fundaciones y asociaciones municipales creadas por el Título VI de la Ley Nº 18.695, en lo referido a los aportes municipales que les sean entregados."
        ],
        "link": ""
    },
    {
        "nombre": "Cultura",
        "coords": [-18.4742, -70.3156],
        "descripcion": "La Dirección Municipal de Cultura, dependerá jerárquicamente de la Administración Municipal y tendrá como función principal fortalecer y promover el desarrollo cultural de la comunidad, considerando la ejecución de un plan de gestión apropiado a las características sociales y culturales de la comuna, de carácter participativo e intersectorial.",
        "director": "Martin Alejandro Romero Zavala",
        "ubicacion": "Centro Cultural de Eventos y Convenciones JAA, Av. General Velásquez 955",
        "correo": [
            "danae.osorio@municipalidadarica.cl"
        ],
        "telefono": [
            "432380561"
        ],
        "funciones": [
            "A) Asesorar al Alcalde en temáticas de interés cultural e integrar esta perspectiva de desarrollo en el diseño e implementación del plan de acción municipal.",
            "B) Diseñar políticas comunales para el desarrollo cultural, con perspectiva de derecho y mediante metodologías inclusivas.",
            "C) Proponer una política integral de gestión que promueva la formación y la promoción de las manifestaciones artísticas de la comunidad local",
            "D) Propender a proteger y resguardar los bienes y monumentos nacionales de la comuna.",
            "E) Administrar el Sistema de Bibliotecas Públicas a través del convenio con DIBAM.",
            "F) Fomentar la creación y mantención de Museos, Bibliotecas y Centros Culturales de la comuna.",
            "G) Planificar, supervisar y evaluar el presupuesto municipal asignado para su ejecución.",
            "H) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo.",
            "I) Otras funciones que el Alcalde le asigne, de acuerdo a la naturaleza de sus funciones."
        ],
        "link": ""
    },
    {
        "nombre": "Desarrollo Comunitario",
        "coords": [-18.4725, -70.2980],
        "descripcion": "La Dirección de Desarrollo Comunitario, dependiente de la Administración Municipal, es la instancia responsable de materializar las acciones en cumplimiento del rol social del Municipio, teniendo por objetivo propender al mejoramiento de la calidad de vida de la población, especialmente de los sectores sociales más vulnerables. Para ello deberá formular un plan de acción anual destinado a la realización de labores de asistencia social, de formación y de promoción comunitaria, aplicando mecanismos de participación que consideren las características de los grupos sociales, organizados o no.",
        "director": "Sandra Flores Contreras",
        "ubicacion": "Belén N°1693",
        "correo": [
            "dideco@municipalidadarica.cl"
        ],
        "telefono": [
            "432380050"
        ],
        "funciones": [
            "Sus funciones generales, de acuerdo al Art. 22 de la ley 18.695 son:",
            "A) Asesorar al Alcalde y Concejo Municipal en la promoción del desarrollo comunitario.",
            "B) Prestar asesoría técnica a las Organizaciones Comunitarias, fomentar su desarrollo, legalización y promover su efectiva participación en el municipio.",
            "C) Proponer, ejecutar, cuando corresponda- medidas tendientes a materializar acciones relacionadas con la salud pública, protección del medioambiente, educación y cultura, capacitación, deporte y recreación, promoción del empleo y turismo.",
            "En el marco del referido Artículo 22 de la Ley, la Dirección de Desarrollo Comunitario deberá cumplir las siguientes funciones:",
            "A) Elaborar un plan de trabajo anual para la gestión social y comunitaria, que contemple ejes transversales, estrategias de intervención e instrumentos de control y seguimiento, en concordancia con el plan anual de acción municipal y el Plan de Desarrollo Comunal vigentes.",
            "B) Dirigir, organizar y supervisar el cumplimiento de los objetivos de la planificación anual.",
            "C) Aprobar los manuales de Procedimientos de los servicios y unidades internas, así como proponer el periodo de actualización de los mismos.",
            "D) Supervisar y dirigir la planificación del trabajo anual de las unidades dependientes.",
            "E) Disponer de información y encomendar la elaboración de diagnósticos a las unidades dependientes que permitan identificar, cuantificar y localizar los problemas sociales que afectan a la comunidad, para la evaluación de las necesidades y la formulación de políticas sociales comunales.",
            "F) Promover la formación, y adecuado funcionamiento de organizaciones comunitarias, prestándoles asistencia técnica en las materias que les competan, con especial énfasis en la participación ciudadana, la gestión comunitaria y los derechos sociales.",
            "G) Implementar programas de promoción comunitaria dirigidos a toda la comunidad para facilitar el acceso a la información en temáticas de interés común.",
            "H) Desarrollar programas de capacitación y de formación para las diversas organizaciones comunitarias, adecuadas a sus necesidades y características.",
            "I) Mantener información actualizada de las organizaciones comunitarias de la comuna.",
            "J) Implementar programas de atención social para los usuarios que califican en grupos sociales prioritarios.",
            "K) Mantener y actualizar un catastro de la red de asistencia y cooperación social de la comuna.",
            "L) Administrar y ejecutar los programas sociales del Estado de acuerdo a los convenios vigentes e implementar evaluaciones periódicas sobre su impacto en el ámbito social.",
            "M) Planificar, dirigir, coordinar y controlar el trabajo operativo de las unidades a su cargo, con el fin de desarrollar una efectiva labor social en coordinación con las demás unidades municipales",
            "N) Establecer coordinaciones internas y de carácter intersectorial con organismos públicos y privados, para la elaboración y ejecución de programas y proyectos en torno a las áreas estratégicas de la Dirección.",
            "O) Supervisar y evaluar permanentemente la gestión técnica y administrativa de los equipos responsables de ejecutar los programas de la Dirección.",
            "P) Supervisar y evaluar el desempeño de los funcionarios, proponiendo al Alcalde la dotación del personal requerida, según las necesidades de cada unidad dependiente.",
            "Q) Integrar el Comité de Emergencia.",
            "R) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo.",
            "S) Cumplir otras funciones que el Alcalde le asigne, en el marco de la legislación vigente."
        ],
        "link": ""
    },
    {
        "nombre": "Desarrollo Rural",
        "coords": [-18.4690, -70.2880],
        "descripcion": "La Dirección de Desarrollo Rural, dependiente de la Administración Municipal, tendrá como función promover el desarrollo sustentable del sector rural de la Comuna, con el fin de incrementar el nivel y calidad de vida de sus habitantes. Los objetivos de esta unidad se enmarcan en la promoción de la igualdad de oportunidades y la integración del sector rural en su rol activo para el desarrollo económico y social de la comuna, como zona de interés turístico y patrimonial. Tendrá la misión de atender las necesidades de sus habitantes y considerará los factores geográficos, económicos y culturales -identificando potencialidades y restricciones para su desarrollo- en la formulación, gestión y coordinación de programas y proyectos.",
        "director": "Arlette Saavedra Castillo",
        "ubicacion": "Avda. 18 de septiembre N°2413",
        "correo": [],
        "telefono": [
            "432380370"
        ],
        "funciones": [
            "A) Velar por el cumplimiento de las acciones estipuladas en las políticas públicas y planes de desarrollo rural a nivel nacional y coordinar los recursos destinados, a través de los Ministerios correspondientes.",
            "B) Elaborar en coordinación con la Dirección de Desarrollo Comunitario y Dirección de Medioambiente, Aseo y Ornato, un programa de desarrollo rural sustentable, aplicable a partir de un enfoque territorial.",
            "C) Canalizar las necesidades de la población rural en capacitación, transferencia tecnológica, investigación y otras.",
            "D) Asesorar a los pobladores del sector rural, en la postulación a fondos públicos y en la ejecución de programas y/o proyectos, en coordinación con los servicios públicos pertinentes.",
            "E) Generar en conjunto con los servicios públicos, programas intersectoriales en fomento del desarrollo rural, a través del Plan Marco Territorial para zonas desconcentradas,",
            "F) Proponer y ejecutar, en conjunto con la Secretaría Comunal de Planificación, proyectos tendientes a mejorar la infraestructura comunitaria y los servicios básicos de los sectores rurales de la comuna.",
            "G) Incentivar la participación del sector privado y de la sociedad civil, mediante la creación de organizaciones para el desarrollo del sector rural.",
            "H) Proponer y fomentar el desarrollo económico de los valles y zona rural en general, a través de programas de capacitación para el emprendimiento.",
            "I) Elaborar y mantener registros y reportes estadísticos periódicos del comportamiento demográfico, económico y social de la zona rural de la comuna de Arica.",
            "J) Desarrollar en conjunto con el Departamento de Emergencia y Protección Civil, planes tendientes a solucionar problemáticas producidas por sismos, desbordes de ríos y otros que afecten a la comunidad rural de Arica.",
            "K) Elaborar programas anuales, en conjunto con la Dirección de Medioambiente, Aseo y Ornato, para el resguardo y preservación del medio ambiente, en el manejo de residuos peligrosos y extinción de micro vertederos, tanto en cauces y ríos, así como también en el manejo y acopio de abonos orgánicos.",
            "L) Proponer estrategias medioambientales para la difusión y extensión del uso de energías limpias, del manejo y reutilización de residuos.",
            "M) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo."
        ],
        "link": ""
    },
    {
        "nombre": "Eventos",
        "coords": [-18.4742, -70.3156],
        "descripcion": "La Oficina de Eventos, dependiente de Alcaldía, será la unidad operativa de soporte técnico y logístico para la realización de actividades masivas del municipio de interés público en general.",
        "director": "Andrés Jesús Uribe Miranda",
        "ubicacion": "Avenida General Velásquez # 955 Centro Cultural Junta de Adelanto",
        "correo": [
            "eventos.municipalidad@municipalidadarica.cl"
        ],
        "telefono": [
            "432380220",
            "432380246"
        ],
        "funciones": [
            "A) Elaborar plan de trabajo anual en coordinación con todas las Direcciones municipales.",
            "B) Colaborar en la planificación de los eventos donde participe el Sr. Alcalde",
            "C) Facilitar el soporte técnico y logístico de todas las actividades masivas que realice el municipio.",
            "D) Coordinar y dirigir las actividades de la oficina dependiente Soporte Audiovisual.",
            "E) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo la oficina."
        ],
        "link": ""
    },
    {
        "nombre": "Gabinete de Alcaldía",
        "coords": [-18.4802, -70.3207],
        "descripcion": "El Gabinete de la Alcaldía dependerá directamente del Alcalde y tiene por objetivo coordinar la labor administrativa que se deriva del funcionamiento de la gestión diaria que desarrolla el Alcalde. También debe coordinar el contenido, la forma y oportunidad en que se entrega la información hacia la comunidad, tanto desde el punto de vista de las relaciones públicas, como de las comunicaciones.",
        "director": "Carolina Szabó Szakal",
        "ubicacion": "Rafael Sotomayor # 415",
        "correo": [
            "carolina.szabo@municipalidadarica.cl",
            "Protocolo: protocolo@municipalidadarica.cl",
            "Secretaria: roxana.duran@municipalidadarica.cl"
        ],
        "telefono": [
            "432380003",
            "432380279"
        ],
        "funciones": [
            "El Gabinete de Alcaldía tendrá las siguientes funciones específicas:",
            "A) Organizar y administrar la agenda de responsabilidades e intervenciones públicas del Alcalde.",
            "B) Coordinar el desarrollo de ceremonias y otras actividades sociales en donde participe la autoridad comunal, a modo de responder los requerimientos de la misma.",
            "C) Cuidar el adecuado cumplimiento de las normas vinculadas con el protocolo en cada una de las actividades públicas donde participe el Alcalde.",
            "D) Coordinar, supervisar y controlar con las Direcciones Municipales todos los eventos que realice el Municipio en beneficio de la comunidad.",
            "E) Coordinar todas las comunicaciones internas municipales que se dan a conocer a la comunidad, en el ámbito de la salud, educación, cultura, turismo, social y otros.",
            "F) Elaborar y proponer un plan de relaciones públicas para la gestión interna y externa, en coordinación con las unidades de Comunicaciones del municipio y de los servicios incorporados,",
            "G) Elaborar y proponer mecanismos que permitan recoger las inquietudes e intereses de la comunidad para asegurar una pronta respuesta ante las situaciones y necesidades emergentes.",
            "H) Atender, registrar y coordinar las audiencias públicas del Alcalde e informar oportunamente a las instancias correspondientes.",
            "I) Ejecutar todos los procedimientos administrativos para la coordinación de invitaciones, saludos y comunicados del Alcalde y Concejo Municipal, resguardando y aplicando el correcto uso de las normas protocolares.",
            "J) Coordinar y gestionar los apoyos operativos y logísticos necesarios para la ejecución de actividades y actos oficiales del Alcalde y del Concejo Municipal, resguardando en todos ellos, los normas protocolares existentes.",
            "K) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo la oficina",
            "L) Cumplir otras funciones que el Alcalde le asigne, de conformidad a la legislación vigente."
        ],
        "link": ""
    },
    {
        "nombre": "Innovación y Desarrollo Institucional",
        "coords": [-18.4803, -70.3205],
        "descripcion": "La Dirección de Innovación y Desarrollo Institucional dependiente de la Administración Municipal, tendrá por función principal, generar los procesos e iniciativas de modernización del municipio, mediante el diseño y aplicación de sistemas de administración informáticos, que permitan el sostenimiento operativo y eficiente de los procedimientos y servicios ofrecidos a la comunidad.",
        "director": "Erwin Jose Montenegro Pacheco",
        "ubicacion": "Rafael Sotomayor #475",
        "correo": [
            "ingenieria.redes@municipalidadarica.cl"
        ],
        "telefono": [
            "432380968"
        ],
        "funciones": [
            "A) Proponer reformas institucionales relativas a los procedimientos administrativos, sus recursos tecnológicos y los sistemas de administración informáticos.",
            "B) Dirigir el diseño e implementación de políticas, planes y programas de desarrollo institucional, bajo un enfoque de innovación y mejoramiento continuo de los servicios",
            "C) Dirigir y supervisar la elaboración de los Sistemas de administración implementados, su puesta en marcha y capacitación en las unidades municipales.",
            "D) Estudiar y velar por el cumplimiento de la estructura de la organización, en cuanto al número de unidades que la componen, a sus encargados y a la distribución de funciones, con especial énfasis en los límites de responsabilidad, en la centralización de decisión y la delegación.",
            "E) Asesorar la implementación de las reformas internas, incorporación de nuevas funciones, modificación de la organización de las unidades y niveles jerárquicos del municipio.",
            "F) Diagnosticar de forma periódica la realidad institucional en el área de la administración y soporte informático, con el fin de proponer las intervenciones necesarias para la toma de decisiones.",
            "G) Evaluar los procedimientos administrativos y de soporte informático, para el fortalecimiento de las competencias administrativas, económicas y políticas de las unidades municipales.",
            "H) Fortalecer las capacidades institucionales de las unidades municipales, mediante el mejoramiento de los procedimientos que dependen de la gestión de éstas.",
            "I) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo."
        ],
        "link": ""
    },
    {
        "nombre": "Juzgado de Policía Local",
        "coords": [-18.4788, -70.3225],
        "descripcion": "Los Juzgados de Policía Local dependerán administrativamente del Alcalde y técnicamente del Poder Judicial. Al I, II y III Juzgado de Policía Local, les corresponde administrar la justicia en la comuna.",
        "director": "Jpl 1: Gonzalo Droguett Marcuello / Jpl 2: Luis Clemente Cerda Pérez / Jpl 3: Coralí Aravena León",
        "ubicacion": "Chacabuco Nº 314, tercer nivel",
        "correo": [
            "1juzgado@municipalidadarica.cl",
            "2juzgado@municipalidadarica.cl",
            "escritos3jpl@municipalidadarica.cl"
        ],
        "telefono": [
            "432380016",
            "432380525",
            "432380031"
        ],
        "funciones": [
            "1. Administrar justicia en las materias que la propia Ley fija, tales como:",
            "A) Infracciones a los preceptos que reglamentan el transporte por calles y caminos y el tránsito público.",
            "B) Infracciones a las Ordenanzas, Reglamentos, Acuerdos Municipales y Decretos de la Alcaldía.",
            "C) Infracciones a la Ley sobre Rentas Municipales.",
            "D) Infracciones a la Ley de Urbanismo y Construcciones y Ordenanza respectiva.",
            "E) Infracciones a la Ley de Educación Primaria y Obligatoria.",
            "F) Infracción a la censura cinematográfica",
            "G) Infracciones a la Ley de Pesca y caza.",
            "H) Infracciones al Registro de Empadronamiento Vecinal.",
            "I) Infracciones a la Ley sobre Pavimentación.",
            "J) Infracciones a la Ley de Alcoholes y Bebidas Alcohólicas.",
            "K) Infracciones a la Ley sobre Espectáculos Públicos, diversiones y carreras.",
            "L) Infracciones a la Ley de Parques y Monumentos Nacionales.",
            "M) Faltas al Código Penal, que no sean reglamentadas por Jueces del Crimen.",
            "N) Algunas disposiciones de la Ley Orgánica Constitucional sobre votaciones populares y escrutinios.",
            "2. Informar a la Corte de Apelaciones las causas pendientes indicando el estado en que se encuentran y los motivos del retardo y paralización que alguna de ella sufriera; de las causas falladas en el mismo período y de las que se encuentren en estado de sentencia si las hubiese.",
            "3. Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo los juzgados."
        ],
        "link": ""
    },
    {
        "nombre": "Medio Ambiente, Aseo y Ornato",
        "coords": [-18.4705, -70.3055],
        "descripcion": "La Dirección de Medioambiente, Aseo y Ornato dependerá jerárquicamente de la Administración Municipal y tendrá como funciones principales proponer y ejecutar medidas tendientes a materializar acciones y programas relacionados con la protección y resguardo del medio ambiente; así como el aseo y mantención de las vías públicas, alumbrado público, áreas verdes y en general de los bienes nacionales de uso público existentes en la comuna.",
        "director": "Marco Gutiérrez Montecino",
        "ubicacion": "Renato Rocca 1539",
        "correo": [],
        "telefono": [
            "432380320"
        ],
        "funciones": [
            "A) El aseo de las vías públicas, parques, plazas, jardines y, en general, de los bienes municipales y nacionales de uso público existentes en la comuna.",
            "B) El servicio de extracción y disposición final de la basura, acciones que se ejecutarán mediante una planificación que coordine y controle la recolección de basura domiciliaria, industrial y comercial, así como su disposición final.",
            "C) El mantenimiento, conservación y administración de las áreas verdes de la comuna.",
            "D) El mantenimiento, y operación del alumbrado público de la Comuna.",
            "E) Proponer y ejecutar medidas tendientes a materializar acciones y programas relacionados con medio ambiente;",
            "F) Aplicar las normas ambientales a ejecutarse en la comuna que sean de su competencia,",
            "G) Elaborar el anteproyecto de ordenanzas municipales relacionadas con el medio ambiente en la Comuna de Arica, para cuya aprobación el Concejo podrá contar con un informe técnico del Ministerio del Medio Ambiente.",
            "H) Coordinar y supervisar a las Empresas subcontratadas que prestan servicios relacionados con el medio ambiente, aseo, áreas verdes y/u ornato de la Comuna.",
            "I) Integrar el Comité de Emergencia y prestar apoyo logístico y de recursos humanos ante situaciones de emergencias y catástrofes.",
            "J) Elaborar planes y programas de gestión ambiental, de acuerdo a los lineamientos fijados en el Plan Regulador Comunal, el Plan de Desarrollo Comunal y en el marco de las normativas y orientaciones de ordenamiento territorial adecuadas a la realidad comunal.",
            "K) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo.",
            "L) Otras funciones que la Ley señale o la autoridad superior le asigne."
        ],
        "link": ""
    },
    {
        "nombre": "Obras Municipales",
        "coords": [-18.4765, -70.3215],
        "descripcion": "La Dirección de Obras Municipales dependerá jerárquicamente de la Administración Municipal y su función primordial es -de acuerdo al D. F.L. N°458 de 1976 y Decreto Supremo N°47 de 1992 L.G.U.C.- aplicar la Ley General de Urbanismo y Construcción, Ordenanza General y Normas Técnicas que regulan las acciones de planificación urbana, urbanización y construcción en el territorio comunal.",
        "director": "Juan Rodrigo Arcaya Puente",
        "ubicacion": "18 Septiembre N° 111, esquina Pedro Montt (acceso por Pedro Montt - estacionamiento)",
        "correo": [
            "direcciondeobras@municipalidadarica.cl"
        ],
        "telefono": [
            "432380911",
            "432380912"
        ],
        "funciones": [
            "Sus funciones de acuerdo al Artículo N° 24 de la Ley 18.695 son:",
            "A) Velar por el cumplimiento de las disposiciones de la Ley General de Urbanismo y Construcción, del Plan Regulador Comunal y de las Ordenanzas correspondientes, para cuyo efecto gozará de las siguientes atribuciones:",
            "• Dar aprobación a las subdivisiones de predios urbanos y urbanos rurales.",
            "• Dar aprobación a los proyectos de obras de urbanización y de construcciones en general, que se efectúen en las áreas urbanas o urbanas rurales. Ellas incluyen tanto las obras nuevas como las ampliaciones, transformaciones y otras que determinen las leyes y reglamentos.",
            "• Otorgar los permisos de edificación de las obras señaladas.",
            "• Fiscalizar la ejecución de dichas obras el momento de su recepción.",
            "• Recibir de las obras ya citadas y autorizar su uso.",
            "B) Realizar tareas de inspección a las obras en uso, a fin de verificar el cumplimiento de las disposiciones legales y técnicas que las rijan.",
            "C) Verificar el cumplimiento de normas ambientales relacionadas con obras de construcción y urbanización al otorgar los permisos correspondientes.",
            "D) Confeccionar y mantener actualizado el catastro de las obras de urbanización y edificación realizadas en la comuna.",
            "E) Proponer y ejecutar medidas relacionadas con la vialidad urbana y rural.",
            "F) Asesorar la construcción de viviendas sociales e infraestructura sanitaria y la prevención de riesgos y prestación de auxilio en situaciones de emergencia.",
            "G) Aplicar las normas generales sobre construcciones y urbanización en la comuna, específicamente las establecidas en la Ley General de Urbanismo y Construcciones (D.F.L. 458/MINVU-1977), la Ordenanza General de dicha Ley (D.S. 47/MINVU-1992), y el Plan Regulador Comunal de Arica vigente.",
            "H) Otras funciones que le asigne el Alcalde, de conformidad con la legislación vigente y que no sea de aquellas que la Ley asigne a otras unidades.",
            "I) Orientar el quehacer de la Dirección de función del Plan de Desarrollo Comunal, y los planes estratégicos que defina la Autoridad comunal"
        ],
        "link": ""
    },
    {
        "nombre": "Prensa y Comunicaciones",
        "coords": [-18.4802, -70.3207],
        "descripcion": "La Oficina de Prensa y Comunicaciones dependerá de la Alcaldía y tendrá las siguientes funciones específicas:",
        "director": "Roberto Puente Fernández del Río",
        "ubicacion": "Rafael Sotomayor # 415",
        "correo": [
            "roberto.puente@municipalidadarica.cl"
        ],
        "telefono": [
            "432380516"
        ],
        "funciones": [
            "A) Elaborar y proponer un plan de comunicaciones de la Municipalidad y los servicios incorporados a su gestión, para mantener oportuna y convenientemente informada a la comunidad de la labor municipal",
            "B) Elaborar y coordinar la distribución de comunicados de prensa, declaraciones públicas y otros medios, que le sean encargados a la unidad.",
            "C) Realizar un seguimiento permanente de los medios de comunicación respecto de la gestión municipal, manteniendo un registro de dichas publicaciones.",
            "D) Preparar el material comunicacional que se le solicite para las diferentes ceremonias y actividades en que participe el municipio.",
            "E) Preparar publicaciones y material audiovisual informando a la comunidad, del quehacer municipal, orientando y educando sobre temas de interés local.",
            "F) Coordinar con las unidades correspondientes la difusión de las actividades municipales.",
            "G) Informar al Alcalde sobre planteamientos relacionados con la administración de la comuna que se publiquen o transmitan a través de los Medios de Comunicación Social o directamente a los habitantes de la comuna.",
            "H) Generar y mantener canales de comunicación y coordinación expeditos con los distintos medios de comunicación social, tanto locales como regionales y nacionales, para asegurar la cobertura que cada actividad municipal requiera y mantener a la comunidad local oportunamente informada.",
            "I) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo la oficina."
        ],
        "link": ""
    },
    {
        "nombre": "Secretaría Comunal de Planificación",
        "coords": [-18.4802, -70.3207],
        "descripcion": "La Secretaría Comunal de Planificación desempeñará funciones de asesoría del Alcalde y del Concejo, en materias de estudios y evaluación, propias de las competencias de ambos órganos municipales. Según lo dispuesto en Art. 21 de la Ley 18.695, le corresponderán las siguientes funciones generales:",
        "director": "Gonzalo Araya Fuentes",
        "ubicacion": "Rafael Sotomayor 415",
        "correo": [
            "viviana.lastra@municipalidadarica.cl",
            "lidice.araya@municipalidadarica.cl"
        ],
        "telefono": [
            "432380205",
            "432380198"
        ],
        "funciones": [
            "A) Desarrollar el plan de trabajo anual de SECPLAN, a través del cual se de cumplimiento a la formulación y ejecución de los instrumentos que norman la gestión municipal.",
            "B) Servir de secretaría técnica permanente del Alcalde y del Concejo Municipal en la formulación de la estrategia municipal, como asimismo de las políticas, planes, programas y proyectos de desarrollo de la comuna.",
            "C) Asesorar al Alcalde en la elaboración de los proyectos del Plan Comunal de Desarrollo y de Presupuesto Municipal; y velar por la observancia de estos instrumentos de planificación y gestión municipal.",
            "D) Evaluar el cumplimiento de los planes, programas, proyectos, inversiones y el presupuesto municipal, e informar sobre estas materias al Concejo Municipal, a lo menos semestralmente;",
            "E) Efectuar análisis y evaluaciones permanentes de la situación de desarrollo de la comuna, con énfasis en los aspectos sociales y territoriales;",
            "F) Elaborar las bases generales y específicas, según corresponda, para los llamados a licitación, previo informe de la unidad competente, de conformidad con los criterios e instrucciones establecidos en el Manual de Procedimientos de Adquisiciones y Contrataciones del municipio.",
            "G) Fomentar vinculaciones de carácter técnico con los servicios públicos y con el sector privado de la comuna.",
            "H) Recopilar y mantener la información comunal y regional atingente a sus funciones.",
            "I) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo la unidad."
        ],
        "link": ""
    },
    {
        "nombre": "Secretaría Municipal",
        "coords": [-18.4802, -70.3207],
        "descripcion": "La Secretaría Municipal dependerá del Alcalde y dirigirá los procedimientos administrativos del Alcalde y del Concejo. La Secretaría Municipal estará a cargo de un Secretario/a Municipal.",
        "director": "Carlos Castillo Galleguillos",
        "ubicacion": "Rafael Sotomayor # 415",
        "correo": [
            "carlos.castillo@municipalidadarica.cl",
            "Secretaria: viviana.munoz@municipalidadarica.cl"
        ],
        "telefono": [],
        "funciones": [
            "A) Dirigir las actividades de Secretaría Administrativa del Alcalde y del Concejo.",
            "B) Desempeñarse como Ministro de Fe en todas las actuaciones municipales.",
            "C) Recibir, mantener y tramitar, cuando corresponda, la Declaración de Intereses establecida por la Ley Nº 18.575.",
            "D) Desarrollar las actividades específicas que le asigne la Ley Orgánica de Municipalidades, en relación a la formación del Consejo Comunal de Organizaciones de la Sociedad Civil, definidos en los artículos 93 al 95.",
            "E) Velar por el cumplimiento de los procedimientos administrativos del municipio.",
            "F) Certificar que las copias son fieles a los originales de los decretos, reglamentos, ordenanzas y resoluciones municipales.",
            "G) Administrar la Oficina de Partes y el Archivo municipal.",
            "H) Autorizar a particulares a consultar el Archivo general municipal y la otorgación de copias de documentos.",
            "I) Cumplir las funciones que le asigna la Ley Nº 19.418, sobre Juntas de Vecinos y demás organizaciones comunitarias.",
            "J) Recibir los antecedentes sobre constitución de nuevas organizaciones comunitarias que soliciten su inscripción en el Registro de Organizaciones Comunitarias- que de conformidad a la Ley Nº 19.418- administra la Secretaría Municipal, para proceder a su inscripción y obtención de la personalidad jurídica.",
            "K) Transcribir las Resoluciones del Alcalde, Actas de acuerdos del Concejo, Consejo Comunal de las Organizaciones de la Sociedad Civil y Comités.",
            "L) Confeccionar los decretos que serán suscritos por el Alcalde, salvo aquellos que por versar sobre materias técnicas específicas, su confección está radicada en la unidad de origen.",
            "M) Ingresar la información requerida por el sistema gubernamental de Receptores de Fondos Públicos.",
            "N) Desempeñarse como Ministro de Fe en la constitución de organizaciones sociales amparadas en la Ley Indígena N° 19.253 y Ley N° 19.537 de Condominios de Viviendas Sociales.",
            "O) Desempañarse como Secretario en las Sesiones ordinarias, extraordinarias del Concejo y del Consejo Comunal de Organizaciones de la Sociedad Civil.",
            "P) Cumplir las labores que la Ley o el Alcalde le designe."
        ],
        "link": ""
    },
    {
        "nombre": "Tránsito y Transporte Público",
        "coords": [-18.4788, -70.3225],
        "descripcion": "La Dirección de Tránsito y Transporte Público, dependerá jerárquicamente de la Administración Municipal, y tendrá como función general aplicar las disposiciones sobre transporte y tránsito públicos, en la forma que determinen las leyes y las normas técnicas de carácter general que dicte el ministerio respectivo.",
        "director": "Ricardo Pizarro Pavéz",
        "ubicacion": "Chacabuco N°314, primer y segundo nivel",
        "correo": [
            "direcciondetransito@municipalidadarica.cl"
        ],
        "telefono": [
            "432380503",
            "432380946",
            "432380504",
            "432380505"
        ],
        "funciones": [
            "A) Determinar el sentido de circulación de vehículos, en coordinación con los organismos de la Administración del Estado competentes.",
            "B) Otorgar y renovar Licencias para conducir vehículos.",
            "C) Otorgar y renovar permisos de circulación.",
            "D) Señalizar adecuadamente las vías públicas.",
            "E) Aplicar las normas generales sobre tránsito y transporte público en la comuna.",
            "F) Supervisar la administración de los Terminales de buses de la comuna.",
            "G) Planificar y ejecutar estudios y catastros, contribuyendo a la optimización del uso de las vías de circulación vehicular y peatonal de la comuna",
            "H) Informar al menos, trimestralmente, al Alcalde (sa), Administración Municipal y a la Unidad de Control, sobre la marcha de los procedimientos que lleva a cabo."
        ],
        "link": ""
    },
    {
        "nombre": "Turismo",
        "coords": [-18.4742, -70.3156],
        "descripcion": "De acuerdo al Reglamento 15/2015, art. 170, La Dirección Municipal de Turismo dependerá de la Administración Municipal y tendrá como función principal la generación de políticas municipales de turismo. Además, le corresponderá la promoción y difusión del turismo local a nivel nacional e internacional y la gestión y mantención de sectores de interés turístico y patrimonial de la comuna.",
        "director": "Josefa Herrera Navarro",
        "ubicacion": "Avenida General Velásquez # 955",
        "correo": [
            "turismomunicipal@municipalidadarica.cl"
        ],
        "telefono": [
            "432380527"
        ],
        "funciones": [
            "A) Proponer políticas, planes, programas y proyectos para el desarrollo del turismo local.",
            "B) Supervisar y evaluar el cumplimiento de políticas, planes, programas y proyectos en el ámbito de su competencia.",
            "C) Proponer proyectos de inversión y promoción turística, en coordinación con la Secretaría Comunal de Planificación, y otras unidades municipales relacionadas con la materia.",
            "D) Realizar evaluaciones periódicas del desarrollo del turismo en la Comuna, proponiendo su optimización a través de planes o programas.",
            "E) Administrar y fiscalizar el bien de uso público, Borde Costero y otros de interés turístico, en conjunto con los unidades municipales correspondientes.",
            "F) Coordinar e implementar acciones en conjunto con organizaciones del sector público y privado, para la promoción, difusión y fomento del turismo local.",
            "G) Supervisar el cumplimiento de las Ordenanzas, Decretos, Convenios y Contratos que mantenga el municipio con terceros en el área turística",
            "H) Mantener un catastro actualizado de la información referida a infraestructuras turísticas bajo administración del municipio en forma directa, o a través de terceros.",
            "I) Formular y proponer programas de capacitación del personal en el ámbito de la planificación del desarrollo turístico."
        ],
        "link": ""
    },
    {
        "nombre": "Prevención y Seguridad Humana (DIPRESEH)",
        "coords": [-18.4805, -70.3195],
        "descripcion": "La Dirección de Prevención y Seguridad Humana (DIPRESEH), dependiente de la Administración Municipal, tendrá como objetivo principal asesorar al Alcalde en materias de prevención y seguridad pública. Además, se encargará de la gestión y coordinación de acciones preventivas frente a situaciones delictuales a nivel local, que permitan incidir en la disminución de la percepción de inseguridad, de acuerdo con lo establecido en la política local. La Dirección Seguridad Pública enfocará principalmente sus acciones en el fortalecimiento de la labor territorial y comunitaria, fortaleciendo el trabajo local con la comunidad, que permita levantar información de primera línea para la elaboración en conjunto de estrategias de trabajo, permitiendo entregar herramientas para el desarrollo local y comunitario, en materia de prevención y seguridad. A través de las acciones que realizará la Dirección, se promoverá la cohesión social, el capital social, la identidad barrial, con cultura de diálogo y solución pacífica de los conflictos, que reporten ambientes de mayor tranquilidad, confianza y convivencia ciudadana.",
        "director": "Esteban Maldonado Ayala",
        "ubicacion": "7 de junio #188, segundo piso",
        "correo": [
            "grace.iribarren@municipalidadarica.cl"
        ],
        "telefono": [
            "432380314"
        ],
        "funciones": [
            "A) Desarrollo, implementación, evaluación, promoción, capacitación y apoyo de acciones de prevención social y situacional en la comuna de Arica.",
            "B) La celebración de convenios con otras entidades públicas para la aplicación de planes de reinserción social y de asistencia a víctimas de situaciones delictuales.",
            "C) Adoptar medidas en el ámbito de la prevención y seguridad ciudadana a nivel comunal, sin perjuicio de las funciones del Ministerio del Interior y Seguridad Pública y de las Fuerzas de Orden y Seguridad.",
            "D) Elaborar, aprobar, ejecutar y evaluar el Plan comunal de seguridad pública, de acuerdo a los lineamientos de la Política Local de Prevención y Seguridad a nivel comunal.",
            "E) Coordinar el Consejo Comunal de Seguridad Pública, órgano consultivo del Alcalde en materia de seguridad pública comunal y será, a lo menos, una instancia de coordinación interinstitucional a nivel local."
        ],
        "link": ""
    },
    {
        "nombre": "Salud",
        "coords": [-18.4680, -70.2950],
        "descripcion": "La Dirección de Salud Municipal (DISAM) administra la atención primaria de salud.",
        "director": "Claudia Villegas",
        "ubicacion": "Av. Argentina 2335",
        "correo": [],
        "telefono": [
            "800 500 100"
        ],
        "funciones": [
            "A) Administrar los Cesfam y postas rurales."
        ],
        "link": "https://apsmuniarica.cl/web/"
    }
];

function renderUnidadesGrid() {
    const grid = $('#grid-unidades');
    grid.empty();

    unidadesMunicipales.forEach((unidad, index) => {
        const btnCol = $('<div>').addClass('col-4 col-md-4 col-lg-3'); 
        
        const btn = $('<button>')
            .addClass('btn btn-primary w-100 h-100 p-2 d-flex align-items-center justify-content-center text-center btn-unidad-municipal')
            .attr('id', 'btn-unidad-' + index)
            .css({
                'min-height': '100px',
                'font-size': '0.9rem',
                'font-weight': 'bold',
                'word-wrap': 'break-word',
                'transition': 'all 0.3s'
            })
            .text(unidad.nombre)
            .on('click', function() {
                if (unidad.link && unidad.link.trim() !== "") {
                    window.open(unidad.link, '_blank');
                    return;
                }

                // Resetear estilos de todos los botones
                $('.btn-unidad-municipal').removeClass('btn-warning').addClass('btn-primary').css('color', '');
                
                // Activar este botón
                $(this).removeClass('btn-primary').addClass('btn-warning').css('color', 'black');

                selectedUnidad = unidad;
                
                // Mostrar botón flotante
                $('#boton-info-unidad').fadeIn();

                // Zoom al mapa y marcador
                if (unidad.coords && osmMap) {
                    // Gestionar marcador
                    if (currentMarker) {
                        osmMap.removeLayer(currentMarker);
                    }
                    currentMarker = L.marker(unidad.coords).addTo(osmMap);
                    
                    // MOVER EL MAPA
                    // Animación de zoom out y luego zoom in (si estamos muy cerca) o directo
                    const currentZoom = osmMap.getZoom();
                    
                    // Si ya estamos cerca, alejamos primero un poco para dar contexto
                    if (currentZoom > 15) {
                        osmMap.flyTo(unidad.coords, 14, {
                            duration: 1.0
                        });
                        setTimeout(() => {
                            osmMap.flyTo(unidad.coords, 18, {
                                duration: 1.5
                            });
                        }, 1100);
                    } else {
                        osmMap.flyTo(unidad.coords, 18, {
                            duration: 2.0
                        });
                    }

                    // MINIMIZAR MENÚ (Solo en móvil para mejorar UX)
                    if (window.innerWidth < 992) { 
                        // Ocultar el contenido de la pestaña activa o colapsar el menú
                        // Una opción simple es remover la clase 'active' temporalmente o ocultar el contenedor
                        // pero para que el usuario pueda volver a abrirlo al pulsar "Direcciones"
                        $('#pills-tabContent').slideUp();
                    }
                }
            });

        btnCol.append(btn);
        grid.append(btnCol);
    });
}

function showUnidadDetalle(unidad) {
    $('#modalDetalleUnidadLabel').text(unidad.nombre);
    
    let content = '<div class="container-fluid">';
    
    // Descripción
    if (unidad.descripcion) {
        content += `<div class="row mb-4"><div class="col-12"><p class="lead" style="font-size: 1rem;">${unidad.descripcion}</p></div></div>`;
    }

    // Info Grid 2x2
    content += '<div class="row g-4 mb-4">';
    
    // Item 1: Director (Top Left) - Green
    content += '<div class="col-md-6">';
    if (unidad.director) {
        content += `
            <div class="p-3 border rounded bg-light h-100">
                <h6 class="fw-bold mb-2" style="color: #00ac8e !important;"><i class="bi bi-person-badge-fill me-2"></i>Director/a</h6>
                <p class="mb-0 text-break">${unidad.director}</p>
            </div>`;
    }
    content += '</div>';

    // Item 2: Correo (Top Right) - Yellow
    content += '<div class="col-md-6">';
    if (unidad.correo && unitHasValue(unidad.correo)) {
        content += `
            <div class="p-3 border rounded bg-light h-100">
                <h6 class="fw-bold mb-2" style="color: #eec85d !important;"><i class="bi bi-envelope-fill me-2"></i>Correo</h6>`;
        if(Array.isArray(unidad.correo)){
             unidad.correo.forEach(mail => content += `<p class="mb-0 text-break"><a href="mailto:${mail}" class="text-decoration-none text-dark">${mail}</a></p>`);
        } else {
             content += `<p class="mb-0 text-break">${unidad.correo}</p>`;
        }
        content += `</div>`;
    }
    content += '</div>';
    
    // Item 3: Ubicación (Bottom Left) - Red
    content += '<div class="col-md-6">';
    if (unidad.ubicacion) {
        content += `
            <div class="p-3 border rounded bg-light h-100">
                <h6 class="fw-bold mb-2" style="color: #d1694e !important;"><i class="bi bi-geo-alt-fill me-2"></i>Ubicación</h6>
                <p class="mb-0 text-break">${unidad.ubicacion}</p>
            </div>`;
    }
    content += '</div>';

    // Item 4: Teléfono (Bottom Right) - Blue
    content += '<div class="col-md-6">';
    if (unidad.telefono && unitHasValue(unidad.telefono)) {
        content += `
            <div class="p-3 border rounded bg-light h-100">
                <h6 class="fw-bold mb-2" style="color: #01a5e2 !important;"><i class="bi bi-telephone-fill me-2"></i>Teléfono</h6>`;
         if(Array.isArray(unidad.telefono)){
             unidad.telefono.forEach(tel => content += `<p class="mb-0 text-break"><a href="tel:${tel}" class="text-decoration-none text-dark">${tel}</a></p>`);
        } else {
             content += `<p class="mb-0 text-break">${unidad.telefono}</p>`;
        }
        content += `</div>`;
    }
    content += '</div>';

    content += '</div>'; // End Row

    // Funciones Section
    if (unidad.funciones) {
         content += `<div class="row"><div class="col-12">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Funciones</h5>
            <div class="card border-0 bg-light"><div class="card-body">`;
         
         if(Array.isArray(unidad.funciones)){
             content += '<ul class="list-group list-group-flush bg-transparent">';
             unidad.funciones.forEach(func => {
                 content += `<li class="list-group-item bg-transparent border-0 ps-0"><i class="bi bi-check2-circle text-primary me-2"></i>${func}</li>`;
             });
             content += '</ul>';
         } else {
             content += unidad.funciones;
         }
         content += `</div></div></div></div>`;
    }
    
    if (!unidad.director && !unidad.descripcion && !unidad.funciones) {
        content += '<div class="alert alert-warning">Información detallada no disponible por el momento. Por favor visite <a href="https://www.muniarica.cl" target="_blank">muniarica.cl</a></div>';
    }

    content += '</div>'; // End container-fluid

    $('#modalDetalleUnidadBody').html(content);
    
    var myModal = new bootstrap.Modal(document.getElementById('modalDetalleUnidad'));
    myModal.show();
}

function unitHasValue(val) {
    if (Array.isArray(val)) return val.length > 0;
    return val && val !== "";
}

$(document).ready(function () {
    // Inicialización de Select2 - Mantener si se usa en otros lados, si no, se puede quitar
    // $("#selectFiltros, #selectArterias").select2(); 

    mostrarPantallaCarga();

    // Capa Satelital Mapbox
    mapboxSatelliteLayer = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-v9/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: '© Mapbox',
        maxZoom: 22,
        accessToken: 'pk.eyJ1IjoibWFwZG9tYXJpY2EiLCJhIjoiY21pbmQzMDJ5MDd4bjNlb2o2dWJxbTc2ZiJ9.waBbNEYRyt2QExyzEq9Xhw',
        className: 'mapbox-satellite-offset'
    });

    // Capa OpenStreetMap
    tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        className: 'osm-layer-offset'
    });

    // Inicializar Mapa
    osmMap = L.map('osmMap', {
        center: [-18.473334791925954, -70.30508438527886],
        zoom: 14,
        layers: [mapboxSatelliteLayer]
    });

    // Control de Capas
    var baseMaps = {
        "OpenStreetMap": tileLayer,
        "Satélite": mapboxSatelliteLayer
    };
    L.control.layers(baseMaps).addTo(osmMap);

    // Inyectar estilos para el efecto "Terremoto/Expansion" (Onda expansiva desde el borde)
    $('<style>@keyframes expansion-wave { 0% { stroke-width: 0; stroke-opacity: 1; } 100% { stroke-width: 50px; stroke-opacity: 0; } } .earthquake-effect { animation: expansion-wave 2s infinite ease-out; transform-box: fill-box; }</style>').appendTo('head');

    // Cargar Capa Recintos (Siempre visible)
    fetch('/didecosistemas/public/capas/recintos.geojson')
        .then(response => response.json())
        .then(data => {
            L.geoJSON(data, {
                style: {
                    color: '#0015ffff', 
                    fillColor: '#0015ffff',
                    fillOpacity: 0.4,   // Relleno estático visible
                    weight: 2,          // Peso base (será sobreescrito por la animación)
                    className: 'earthquake-effect' // Clase animada
                },
                onEachFeature: function(feature, layer) {
                    // Intenta mostrar el nombre si existe en las propiedades
                    if (feature.properties && (feature.properties.Nombre || feature.properties.name)) {
                        layer.bindPopup(feature.properties.Nombre || feature.properties.name);
                    }
                }
            }).addTo(osmMap);
        })
        .catch(err => console.error('Error cargando recintos.geojson:', err));

    $('#satelital').prop('checked', true);
    
    // Renderizar Directorio
    renderUnidadesGrid();

    // Listener Botón Flotante Información
    $('#boton-info-unidad button').on('click', function() {
        if (selectedUnidad) {
            showUnidadDetalle(selectedUnidad);
        }
    });

    ocultarPantallaCarga();

    // Lógica para el botón "Direcciones" (Toggle del menú)
    $('#pills-buscar-tab').on('click', function(e) {
        // Prevenir comportamiento por defecto si es necesario, aunque bootstrap maneja tabs
        
        // Toggle (Mostrar/Ocultar) el contenido
        const content = $('#pills-tabContent');
        
        if (content.is(':visible')) {
            // Si está visible, lo ocultamos (pero mantenemos el botón activo visualmente si se desea)
            content.slideUp();
        } else {
            // Si está oculto, lo mostramos
            content.slideDown();
        }
    });

    // Asegurar que el contenedor de grid esté visible al iniciar (especialmente en móvil)
    if (window.innerWidth < 992) {
        $('#pills-tabContent').show();
    }

});
