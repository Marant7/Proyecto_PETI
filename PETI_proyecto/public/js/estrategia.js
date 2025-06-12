// Función principal para actualizar totales de una matriz específica
function updateTotals(matrixType) {
    const table = document.querySelector(`[data-matrix="${matrixType}"]`);
    if (!table) return;
    
    // Obtener todas las filas que contienen selects (excluyendo la fila de totales)
    const dataRows = table.querySelectorAll('tbody tr:not(.total-row)');
    
    // Inicializar totales para cada columna
    const columnTotals = [0, 0, 0, 0];
    
    // Recorrer cada fila de datos
    dataRows.forEach(row => {
        const selects = row.querySelectorAll('select');
        selects.forEach((select, columnIndex) => {
            // Obtener el valor seleccionado y convertir a número
            const value = parseInt(select.value) || 0;
            // Sumar al total de la columna correspondiente
            columnTotals[columnIndex] += value;
        });
    });
    
    // Actualizar las celdas de totales en la interfaz
    for (let i = 0; i < 4; i++) {
        const totalCell = document.getElementById(`total-${matrixType}-col${i + 1}`);
        if (totalCell) {
            totalCell.textContent = columnTotals[i];
        }
    }
    
    // Calcular y actualizar el total general de la matriz
    const generalTotal = columnTotals.reduce((sum, total) => sum + total, 0);
    const generalTotalSpan = document.getElementById(`total-${matrixType}-general`);
    if (generalTotalSpan) {
        generalTotalSpan.textContent = generalTotal;
    }

    // Actualizar la puntuación en la tabla de síntesis
    updateSintesisPuntuacion(matrixType, generalTotal);

    // Log para debugging
    // console.log(`Totales de matriz ${matrixType.toUpperCase()}:`, {
    //     'Columna 1': columnTotals[0],
    //     'Columna 2': columnTotals[1],
    //     'Columna 3': columnTotals[2],
    //     'Columna 4': columnTotals[3],
    //     'Total General': generalTotal
    // });
}

// Función para actualizar la puntuación en la tabla de síntesis
function updateSintesisPuntuacion(matrixType, total) {
    // Relacionar el tipo de matriz con la fila de síntesis
    const relacionMap = {
        'fo': 'FO',
        'fa': 'AF',
        'da': 'AD',
        'do': 'OD'
    };
    const relacion = relacionMap[matrixType];
    if (!relacion) return;

    // Buscar la fila correspondiente en la tabla de síntesis
    const rows = document.querySelectorAll('.sintesis-table tbody tr');
    rows.forEach(row => {
        const relacionCell = row.querySelector('.relacion-cell');
        const puntuacionCell = row.querySelector('.puntuacion-cell');
        if (relacionCell && puntuacionCell && relacionCell.textContent.trim() === relacion) {
            puntuacionCell.textContent = total;
        }
    });
}

// Función para obtener todos los valores de una matriz
function getMatrixValues(matrixType) {
    const table = document.querySelector(`[data-matrix="${matrixType}"]`);
    if (!table) return {};
    
    const values = {};
    const dataRows = table.querySelectorAll('tbody tr:not(.total-row)');
    
    dataRows.forEach((row, rowIndex) => {
        const rowLabel = row.querySelector('.label-cell').textContent;
        values[rowLabel] = {};
        
        const selects = row.querySelectorAll('select');
        selects.forEach((select, colIndex) => {
            const colLabel = table.querySelectorAll('thead th')[colIndex + 1].textContent;
            values[rowLabel][colLabel] = select.value || '0';
        });
    });
    
    return values;
}

// Función para obtener todos los valores de todas las matrices
function getAllMatrixValues() {
    return {
        'Fortalezas_vs_Oportunidades': getMatrixValues('fo'),
        'Fortalezas_vs_Amenazas': getMatrixValues('fa'),
        'Debilidades_vs_Oportunidades': getMatrixValues('do'),
        'Debilidades_vs_Amenazas': getMatrixValues('da')
    };
}

// NUEVA FUNCIÓN: Obtener datos de la tabla de síntesis
function getSintesisData() {
    const sintesisData = {};
    const rows = document.querySelectorAll('.sintesis-table tbody tr');
    
    rows.forEach(row => {
        const relacionCell = row.querySelector('.relacion-cell');
        const estrategiaCell = row.querySelector('.estrategia-cell');
        const puntuacionCell = row.querySelector('.puntuacion-cell');
        const descripcionCell = row.querySelector('.descripcion-cell');
        
        if (relacionCell && estrategiaCell && puntuacionCell && descripcionCell) {
            const relacion = relacionCell.textContent.trim();
            sintesisData[relacion] = {
                tipologia_estrategia: estrategiaCell.textContent.trim(),
                puntuacion: parseInt(puntuacionCell.textContent.trim()) || 0,
                descripcion: descripcionCell.textContent.trim()
            };
        }
    });
    
    return sintesisData;
}

// NUEVA FUNCIÓN: Guardar síntesis en la base de datos
async function guardarSintesis() {
    try {
        // Mostrar indicador de carga
        const botonGuardar = document.getElementById('btn-guardar-sintesis');
        if (botonGuardar) {
            botonGuardar.disabled = true;
            botonGuardar.textContent = '⏳ Guardando...';
            botonGuardar.style.backgroundColor = '#cccccc';
        }
        
        // Obtener datos de la síntesis
        const estrategias = getSintesisData();
        
        // Validar que hay datos
        if (Object.keys(estrategias).length === 0) {
            throw new Error('No hay datos de síntesis para guardar');
        }
        
        // Preparar datos para enviar
        const datosEnvio = {
            estrategias: estrategias
        };
        
        // Enviar petición AJAX
        const response = await fetch('guardar_sintesis.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datosEnvio)
        });
        
        const resultado = await response.json();
        
        if (resultado.exito) {
            mostrarMensaje('Síntesis guardada correctamente', 'success');
        } else {
            throw new Error(resultado.mensaje || 'Error desconocido al guardar');
        }
        
    } catch (error) {
        console.error('Error al guardar síntesis:', error);
        mostrarMensaje('Error al guardar: ' + error.message, 'error');
    } finally {
        // Restaurar botón
        const botonGuardar = document.getElementById('btn-guardar-sintesis');
        if (botonGuardar) {
            botonGuardar.disabled = false;
            botonGuardar.textContent = '💾 Guardar Síntesis';
            botonGuardar.style.backgroundColor = '#4CAF50';
        }
    }
}

// NUEVA FUNCIÓN: Mostrar mensajes al usuario
function mostrarMensaje(mensaje, tipo = 'info') {
    // Crear elemento de mensaje si no existe
    let mensajeDiv = document.getElementById('mensaje-sintesis');
    if (!mensajeDiv) {
        mensajeDiv = document.createElement('div');
        mensajeDiv.id = 'mensaje-sintesis';
        mensajeDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            max-width: 300px;
            transition: opacity 0.3s ease;
        `;
        document.body.appendChild(mensajeDiv);
    }
    
    // Configurar estilo según tipo
    switch (tipo) {
        case 'success':
            mensajeDiv.style.backgroundColor = '#4CAF50';
            break;
        case 'error':
            mensajeDiv.style.backgroundColor = '#f44336';
            break;
        default:
            mensajeDiv.style.backgroundColor = '#2196F3';
    }
    
    // Mostrar mensaje
    mensajeDiv.textContent = mensaje;
    mensajeDiv.style.opacity = '1';
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        mensajeDiv.style.opacity = '0';
    }, 3000);
}

// Función removida: autoGuardarSintesis() - Ya no se usa auto-guardado

// Función para exportar datos (útil para debugging o exportación)
function exportData() {
    const data = {
        matrices: getAllMatrixValues(),
        sintesis: getSintesisData()
    };
    console.log('Datos completos del análisis FODA:', JSON.stringify(data, null, 2));
    return data;
}

// Inicializar totales cuando la página carga
document.addEventListener('DOMContentLoaded', function() {
    // Pequeño delay para asegurar que todo esté cargado
    setTimeout(() => {
        updateTotals('fo');
        updateTotals('fa');
        updateTotals('do');
        updateTotals('da');
        
        // Agregar botón de guardar si no existe
        agregarBotonGuardar();
        
        // Configurar actualización de totales cuando cambien los selects
// Configurar actualización de totales cuando cambien los selects
const selects = document.querySelectorAll('select');
console.log('Total de selects encontrados:', selects.length);

selects.forEach((select, index) => {
    console.log(`Configurando select ${index}`);
    
    select.addEventListener('change', (event) => {
        console.log('Select cambió, valor:', event.target.value);
        
        const {matrixType, fila, columna} = obtenerDatosCelda(select);
        const valor = select.value || '0';
        
        console.log('Datos para guardar:', {matrixType, fila, columna, valor});
        
        if (matrixType) {
            // Actualizar totales
            updateTotals(matrixType);
            
            // Guardar automáticamente
            guardarCeldaMatriz(matrixType, fila, columna, valor);
        } else {
            console.error('No se pudo obtener matrixType');
        }
    });
});

// NUEVO: Cargar datos al inicializar
cargarMatricesGuardadas();
    }, 100);
});

// También actualizar totales cuando la página sea visible (para casos de cache)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        updateTotals('fo');
        updateTotals('fa');
        updateTotals('do');
        updateTotals('da');
    }
});

// NUEVA FUNCIÓN: Agregar botón de guardar síntesis
function agregarBotonGuardar() {
    const sintesisSection = document.querySelector('.sintesis-section');
    if (!sintesisSection) return;
    
    // Verificar si ya existe el botón
    if (document.getElementById('btn-guardar-sintesis')) return;
    
    // Crear botón
    const botonGuardar = document.createElement('button');
    botonGuardar.id = 'btn-guardar-sintesis';
    botonGuardar.textContent = '💾 Guardar Síntesis';
    botonGuardar.style.cssText = `
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 12px 25px;
        margin-top: 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    `;
    
    // Agregar hover effect
    botonGuardar.addEventListener('mouseenter', () => {
        botonGuardar.style.backgroundColor = '#45a049';
        botonGuardar.style.transform = 'translateY(-2px)';
        botonGuardar.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
    });
    botonGuardar.addEventListener('mouseleave', () => {
        botonGuardar.style.backgroundColor = '#4CAF50';
        botonGuardar.style.transform = 'translateY(0)';
        botonGuardar.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
    });
    
    // Agregar evento click
    botonGuardar.addEventListener('click', guardarSintesis);
    
    // Agregar al DOM
    sintesisSection.appendChild(botonGuardar);
}
// Función para guardar automáticamente una celda
// Función mejorada para guardar automáticamente una celda
async function guardarCeldaMatriz(tipoMatriz, fila, columna, valor) {
    console.log('Intentando guardar:', {tipoMatriz, fila, columna, valor});
    
    try {
        const response = await fetch('guardar_celda_matriz.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                tipo_matriz: tipoMatriz,
                fila: fila,
                columna: columna,
                valor: valor
            })
        });
        
        console.log('Response status:', response.status);
        const texto = await response.text();
        console.log('Response text:', texto);
        
        let resultado;
        try {
            resultado = JSON.parse(texto);
            console.log('Resultado parseado:', resultado);
        } catch (e) {
            console.error('Error al parsear JSON:', e);
            console.log('Texto recibido:', texto);
            return;
        }
        
        if (!resultado.exito) {
            console.error('Error al guardar celda:', resultado.mensaje);
        } else {
            console.log('Celda guardada exitosamente');
        }
    } catch (error) {
        console.error('Error en la petición:', error);
    }
}

// Función mejorada para obtener datos de fila y columna
function obtenerDatosCelda(select) {
    const row = select.closest('tr');
    const table = select.closest('[data-matrix]');
    const matrixType = table.getAttribute('data-matrix');
    
    // Obtener la fila
    const fila = row.querySelector('.label-cell').textContent.trim();
    
    // Obtener la columna basándose en el índice del select
    const selects = row.querySelectorAll('select');
    const selectIndex = Array.from(selects).indexOf(select);
    
    // Obtener headers de columna
    const headers = table.querySelectorAll('thead th');
    const columna = headers[selectIndex + 1].textContent.trim(); // +1 porque el primer th es vacío
    
    console.log('Datos de celda obtenidos:', {matrixType, fila, columna, selectIndex});
    
    return {matrixType, fila, columna};
}
// Función para cargar valores guardados de las matrices
async function cargarMatricesGuardadas() {
    try {
        const res = await fetch('obtener_matrices.php');
        const data = await res.json();
        if (!data || !Array.isArray(data)) return;

        data.forEach(celda => {
            // Busca el select por tipo de matriz, fila y columna
            const tabla = document.querySelector(`table[data-matrix="${celda.tipo_matriz}"]`);
            if (!tabla) return;
            const select = tabla.querySelector(
                `select[data-fila="${celda.fila}"][data-columna="${celda.columna}"]`
            );
            if (select) select.value = celda.valor;
        });

        // Opcional: recalcula los totales después de cargar
        ['fo', 'fa', 'do', 'da'].forEach(updateTotals);

    } catch (e) {
        console.error('Error cargando matrices guardadas', e);
    }
}

document.addEventListener('DOMContentLoaded', cargarMatricesGuardadas);

document.addEventListener('DOMContentLoaded', function() {
    const btnGuardar = document.getElementById('btn-guardar-matrices');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', guardarTodasLasMatrices);
    }
});

async function guardarTodasLasMatrices() {
    const mensaje = document.getElementById('mensaje-guardar-matrices');
    mensaje.textContent = 'Guardando...';

    // Recorre todas las matrices y sus selects
    const matrices = ['fo', 'fa', 'do', 'da'];
    let datos = [];

    matrices.forEach(tipo => {
        const tabla = document.querySelector(`table[data-matrix="${tipo}"]`);
        if (!tabla) return;
        const filas = tabla.querySelectorAll('tbody tr:not(.total-row)');
        filas.forEach((filaTr, i) => {
            const filaLabel = filaTr.querySelector('.label-cell')?.textContent.trim();
            const selects = filaTr.querySelectorAll('select');
            selects.forEach((select, j) => {
                // Obtén la columna desde el thead
                const ths = tabla.querySelectorAll('thead th');
                const columnaLabel = ths[j + 1]?.textContent.trim(); // +1 porque th[0] es vacío
                const valor = select.value === "" ? null : parseInt(select.value);
                datos.push({
                    tipo_matriz: tipo,
                    fila: filaLabel,
                    columna: columnaLabel,
                    valor: valor
                });
            });
        });
    });

    // Envía los datos al backend
    try {
        const res = await fetch('guardar_matrices.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({matrices: datos})
        });
        const result = await res.json();
        if (result.exito) {
            mensaje.style.color = 'green';
            mensaje.textContent = '¡Matrices guardadas!';
        } else {
            mensaje.style.color = 'red';
            mensaje.textContent = 'Error al guardar: ' + (result.mensaje || 'Desconocido');
        }
    } catch (e) {
        mensaje.style.color = 'red';
        mensaje.textContent = 'Error de red o servidor';
    }
    setTimeout(() => mensaje.textContent = '', 3000);
}