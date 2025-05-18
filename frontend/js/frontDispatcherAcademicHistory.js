const API_URL = '../backend/server.php/academicHistorys';

document.addEventListener('DOMContentLoaded', () => 
{
    const academicHistoryForm = document.getElementById('academicHistoryForm');
    const academicHistoryTableBody = document.getElementById('academicHistoryTableBody');
    const studentNameInput = document.getElementById('student_name');
    const subjectNameInput = document.getElementById('subject_name');
    const approvedInput = document.getElementById('approved');
    const academicHistoryIdInput = document.getElementById('academicHistoryId');

    // Leer todos los estudiantes al cargar
    fetchAcademicHistorys();

    // Formulario: Crear o actualizar estudiante
    academicHistoryForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            student_name: studentNameInput.value,
            subject_name: subjectNameInput.value,
            approved: approvedInput.value,
        };

        const id = academicHistoryIdInput.value;
        const method = id ? 'PUT' : 'POST';
        if (id) formData.id = id;

        try 
        {
            const response = await fetch(API_URL, {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData),
            });

            if (response.ok) {
                academicHistoryForm.reset();
                academicHistoryIdInput.value = '';
                await fetchAcademicHistorys();
            } else {
                alert("Error al guardar");
            }
        } catch (err) {
            console.error(err);
        }
    });

    // Obtener estudiantes y renderizar tabla
    async function fetchAcademicHistorys() 
    {
        try 
        {
            const res = await fetch(API_URL);
            const academicHistorys = await res.json();

            //Limpiar tabla de forma segura.
            academicHistoryTableBody.replaceChildren();
            //acá innerHTML es seguro a XSS porque no hay entrada de usuario
            //igual no lo uso.
            //academicHistoryTableBody.innerHTML = "";

            academicHistorys.forEach(academicHistory => {
                const tr = document.createElement('tr');

                const tdStudent = document.createElement('td');
                tdStudent.textContent = academicHistory.student_name;

                const tdSubject = document.createElement('td');
                tdSubject.textContent = academicHistory.subject_name;

                const tdApproved = document.createElement('td');
                tdApproved.textContent = academicHistory.approved;

                const tdActions = document.createElement('td');
                const editBtn = document.createElement('button');
                editBtn.textContent = 'Editar';
                editBtn.classList.add('w3-button', 'w3-blue', 'w3-small', 'w3-margin-right');
                editBtn.onclick = () => {
                    studentNameInput.value = academicHistory.student_name;
                    subjectNameInput.value = academicHistory.subject_name;
                    approvedInput.value = academicHistory.approved;
                    academicHistoryIdInput.value = academicHistory.id;
                };

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Borrar';
                deleteBtn.classList.add('w3-button', 'w3-red', 'w3-small');
                deleteBtn.onclick = () => deleteAcademicHistory(academicHistory.id);

                tdActions.appendChild(editBtn);
                tdActions.appendChild(deleteBtn);

                tr.appendChild(tdStudent);
                tr.appendChild(tdSubject);
                tr.appendChild(tdApproved);
                tr.appendChild(tdActions);

                academicHistoryTableBody.appendChild(tr);
            });
        } catch (err) {
            console.error("Error al obtener estudiantes:", err);
        }
    }

    // Eliminar estudiante
    async function deleteAcademicHistory(id) 
    {
        if (!confirm("¿Seguro que querés borrar esta información?")) return;

        try 
        {
            const response = await fetch(API_URL, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id }),
            });

            if (response.ok) {
                await fetchAcademicHistorys();
            } else {
                alert("Error al borrar");
            }
        } catch (err) {
            console.error(err);
        }
    }
});