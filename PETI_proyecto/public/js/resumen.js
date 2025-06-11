document.addEventListener('DOMContentLoaded', () => {

    const collapsibleSections = document.querySelectorAll('.collapsible-section');

    collapsibleSections.forEach(section => {
        const header = section.querySelector('.collapsible-header');
        const content = section.querySelector('.collapsible-content');

        if (header && content) {
            content.style.maxHeight = null; 
            header.addEventListener('click', () => {
                section.classList.toggle('open');
                if (section.classList.contains('open')) {
                    content.style.display = 'block';
                    const scrollHeight = content.scrollHeight + "px";
                    content.style.maxHeight = scrollHeight;
                } else {
                    content.style.maxHeight = null;
                }
            });
        }
    });

    const editableTextAreas = document.querySelectorAll('.editable-text-area-plan');
    editableTextAreas.forEach(textarea => {
        function autoResizeEditable() {
            textarea.style.height = 'auto'; 
            textarea.style.height = textarea.scrollHeight + 'px'; 
        }
        textarea.addEventListener('input', autoResizeEditable);
        autoResizeEditable(); 
    });

    const exportButton = document.getElementById('exportPlanDataButton'); 
    if (exportButton) {
        exportButton.addEventListener('click', function () {
            const elementToPrint = document.querySelector('.container-resumen-vista'); 

            if (!elementToPrint) {
                Swal.fire('Error', 'No se encontró el contenido para exportar.', 'error');
                return;
            }


            const opt = {
                margin:       [0.5, 0.5, 0.5, 0.5],
                filename:     'Plan_Estrategico_TI.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: false, useCORS: true },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            Swal.fire({
                title: 'Generando PDF...',
                text: 'Por favor, espere.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const originallyOpenSections = [];
            collapsibleSections.forEach(section => {
                if (section.classList.contains('open')) {
                    originallyOpenSections.push(section);
                } else {

                    const header = section.querySelector('.collapsible-header');
                    if(header) header.click(); 
                }
            });
            editableTextAreas.forEach(textarea => {
                textarea.style.height = textarea.scrollHeight + 'px';
            });

            exportButton.style.display = 'none';

            setTimeout(() => {
                html2pdf().from(elementToPrint).set(opt).save().then(function () {
                    Swal.close(); 

                    exportButton.style.display = ''; 
                    
                    collapsibleSections.forEach(section => {
                        const content = section.querySelector('.collapsible-content');
                        if (!originallyOpenSections.includes(section)) {
                            if (section.classList.contains('open')) {
                                const header = section.querySelector('.collapsible-header');
                                if(header) header.click(); // Simular clic para cerrar
                            }
                        } else {
                            if (content && section.classList.contains('open')) {
                                 content.style.maxHeight = content.scrollHeight + "px";
                            }
                        }
                    });
                    editableTextAreas.forEach(textarea => {
                        textarea.style.height = 'auto'; 
                        textarea.style.height = textarea.scrollHeight + 'px'; 
                    });


                }).catch(function (error) {
                    Swal.fire('Error', 'No se pudo generar el PDF: ' + error, 'error');
                    exportButton.style.display = '';
                    collapsibleSections.forEach(section => {
                         if (!originallyOpenSections.includes(section)) {
                            if (section.classList.contains('open')) {
                                const header = section.querySelector('.collapsible-header');
                                if(header) header.click();
                            }
                        }
                    });
                });
            }, 500);

        });
    }

    const saveButton = document.getElementById('savePlanDataButton');
    if (saveButton && saveButton !== exportButton) { 
        saveButton.addEventListener('click', () => {
            const estrategia = document.getElementById('estrategiaPlanInput').value;
            const conclusiones = document.getElementById('conclusionesPlanInput').value;
            const idEmpresa = document.getElementById('id_empresa_plan').value;

            console.log("ID Empresa (para guardar):", idEmpresa);
            console.log("Estrategia a guardar:", estrategia);
            console.log("Conclusiones a guardar:", conclusiones);
            
            Swal.fire({
                title: 'Guardado (Simulación)',
                text: 'Los datos de Estrategia y Conclusiones se han mostrado en la consola. El guardado real está pendiente.',
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        });
    }
});