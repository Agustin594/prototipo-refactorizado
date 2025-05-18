const API_URL = '../backend/server.php/subjects';

document.addEventListener('DOMContentLoaded', () => 
{
    const subjectForm = document.getElementById('subjectForm');
    const subjectTableBody = document.getElementById('subjectTableBody');
    const subjectNameInput = document.getElementById('subject_name');
    const teacherInput = document.getElementById('teacher');
    const subjectIdInput = document.getElementById('subjectId');

    // Leer todos los estudiantes al cargar
    fetchSubjects();

    // Formulario: Crear o actualizar estudiante
    subjectForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            subject_name: subjectNameInput.value,
            teacher: teacherInput.value,
        };

        const id = subjectIdInput.value;
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
                subjectForm.reset();
                subjectIdInput.value = '';
                await fetchSubjects();
            } else {
                alert("Error al guardar");
            }
        } catch (err) {
            console.error(err);
        }
    });

    // Obtener estudiantes y renderizar tabla
    async function fetchSubjects() 
    {
        try 
        {
            const res = await fetch(API_URL);
            const subjects = await res.json();

            //Limpiar tabla de forma segura.
            subjectTableBody.replaceChildren();
            //acá innerHTML es seguro a XSS porque no hay entrada de usuario
            //igual no lo uso.
            //subjectTableBody.innerHTML = "";

            subjects.forEach(subject => {
                const tr = document.createElement('tr');

                const tdName = document.createElement('td');
                tdName.textContent = subject.subject_name;

                const tdTeacher = document.createElement('td');
                tdTeacher.textContent = subject.teacher;

                const tdActions = document.createElement('td');
                const editBtn = document.createElement('button');
                editBtn.textContent = 'Editar';
                editBtn.classList.add('w3-button', 'w3-blue', 'w3-small', 'w3-margin-right');
                editBtn.onclick = () => {
                    subjectNameInput.value = subject.subject_name;
                    teacherInput.value = subject.teacher;
                    subjectIdInput.value = subject.id;
                };

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Borrar';
                deleteBtn.classList.add('w3-button', 'w3-red', 'w3-small');
                deleteBtn.onclick = () => deleteSubject(subject.id);

                tdActions.appendChild(editBtn);
                tdActions.appendChild(deleteBtn);

                tr.appendChild(tdName);
                tr.appendChild(tdTeacher);
                tr.appendChild(tdActions);

                subjectTableBody.appendChild(tr);
            });
        } catch (err) {
            console.error("Error al obtener estudiantes:", err);
        }
    }

    // Eliminar estudiante
    async function deleteSubject(id) 
    {
        if (!confirm("¿Seguro que querés borrar esta materia?")) return;

        try 
        {
            const response = await fetch(API_URL, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id }),
            });

            if (response.ok) {
                await fetchSubjects();
            } else {
                alert("Error al borrar");
            }
        } catch (err) {
            console.error(err);
        }
    }
});