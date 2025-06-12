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
            botonGuardar.textContent = 'Guardando...';
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
            botonGuardar.textContent = 'Guardar Síntesis';
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

// NUEVA FUNCIÓN: Guardar automáticamente cuando cambien las puntuaciones
function autoGuardarSintesis() {
    // Debounce para evitar múltiples guardados
    clearTimeout(window.autoGuardarTimeout);
    window.autoGuardarTimeout = setTimeout(() => {
        guardarSintesis();
    }, 2000); // Esperar 2 segundos después del último cambio
}

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
        
        // Configurar auto-guardado cuando cambien los selects
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', () => {
                // Primero actualizar totales, luego auto-guardar
                const matrixType = select.closest('[data-matrix]')?.getAttribute('data-matrix');
                if (matrixType) {
                    updateTotals(matrixType);
                    autoGuardarSintesis();
                }
            });
        });
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
    botonGuardar.textContent = 'Guardar Síntesis';
    botonGuardar.style.cssText = `
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        margin-top: 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    `;
    
    // Agregar hover effect
    botonGuardar.addEventListener('mouseenter', () => {
        botonGuardar.style.backgroundColor = '#45a049';
    });
    botonGuardar.addEventListener('mouseleave', () => {
        botonGuardar.style.backgroundColor = '#4CAF50';
    });
    
    // Agregar evento click
    botonGuardar.addEventListener('click', guardarSintesis);
    
    // Agregar al DOM
    sintesisSection.appendChild(botonGuardar);
}