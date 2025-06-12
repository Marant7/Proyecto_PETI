
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

            // NUEVO: Actualizar la puntuación en la tabla de síntesis
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
        
        // NUEVO: Función para actualizar la puntuación en la tabla de síntesis
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
        
        // Función para exportar datos (útil para debugging o exportación)
        function exportData() {
            const data = getAllMatrixValues();
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
    