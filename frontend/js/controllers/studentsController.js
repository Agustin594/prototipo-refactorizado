import { studentsAPI } from '../api/studentsAPI.js';

document.addEventListener('DOMContentLoaded', () => 
{
    loadStudents();
    setupFormHandler();
    setupCancelHandler();
});
  
function setupFormHandler()
{
    const form = document.getElementById('studentForm');
    form.addEventListener('submit', async e => 
    {
        e.preventDefault();
        const student = getFormData();

        if (!student.firstName || !student.lastName || !student.email || !student.age) {
            alert("Todos los campos son obligatorios.");
            return;
        }

        if (!validateEmail(student.email)) {
            alert("El email no es válido.");
            return;
        }

        if (student.age < 16 || student.age > 100) {
            alert("La edad debe ser un número válido.");
            return;
        }
    
        try 
        {
            if (student.id) 
            {
                await studentsAPI.update(student);
            } 
            else 
            {
                await studentsAPI.create(student);
            }
            clearForm();
            loadStudents();
        }
        catch (err)
        {
            alert("El email ya está asignado a un estudiante.");
        }
    });
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function setupCancelHandler()
{
    const cancelBtn = document.getElementById('cancelBtn');
    cancelBtn.addEventListener('click', () => 
    {
        document.getElementById('studentId').value = '';
    });
}
  
function getFormData()
{
    return {
        id: document.getElementById('studentId').value.trim(),
        firstName: document.getElementById('first_name').value.trim(),
        lastName: document.getElementById('last_name').value.trim(),
        email: document.getElementById('email').value.trim(),
        age: parseInt(document.getElementById('age').value.trim(), 10)
    };
}
  
function clearForm()
{
    document.getElementById('studentForm').reset();
    document.getElementById('studentId').value = '';
}
  
async function loadStudents()
{
    try 
    {
        const students = await studentsAPI.fetchAll();
        renderStudentTable(students);
    } 
    catch (err) 
    {
        console.error('Error cargando estudiantes:', err.message);
        errormessage();
    }
}

function errormessage(){
    const tbody = document.getElementById('studentTableBody');
    tbody.replaceChildren();

    const tr = document.createElement('tr');

    tr.appendChild(createErrorCell());

    tbody.appendChild(tr);
}

function createErrorCell(){
    const td = document.createElement('td');
    td.textContent = "Error cargando estudiantes.";
    return td;
}
  
function renderStudentTable(students)
{
    const tbody = document.getElementById('studentTableBody');
    tbody.replaceChildren();
  
    students.forEach(student => 
    {
        const tr = document.createElement('tr');
        
        tr.appendChild(createCell(student.first_name + " " + student.last_name));
        tr.appendChild(createCell(student.email));
        tr.appendChild(createCell(student.age.toString()));
        tr.appendChild(createActionsCell(student));
    
        tbody.appendChild(tr);
    });
}
  
function createCell(text)
{
    const td = document.createElement('td');
    td.textContent = text;
    return td;
}
  
function createActionsCell(student)
{
    const td = document.createElement('td');
  
    const editBtn = document.createElement('button');
    editBtn.textContent = 'Editar';
    editBtn.className = 'w3-button w3-blue w3-small';
    editBtn.addEventListener('click', () => fillForm(student));
  
    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Borrar';
    deleteBtn.className = 'w3-button w3-red w3-small w3-margin-left';
    deleteBtn.addEventListener('click', () => confirmDelete(student.id));
  
    td.appendChild(editBtn);
    td.appendChild(deleteBtn);
    return td;
}
  
function fillForm(student)
{
    document.getElementById('studentId').value = student.id;
    document.getElementById('first_name').value = student.first_name;
    document.getElementById('last_name').value = student.last_name;
    document.getElementById('email').value = student.email;
    document.getElementById('age').value = student.age;
}
  
async function confirmDelete(id) 
{
    if (!confirm('¿Estás seguro que deseas borrar este estudiante?')) return;
  
    try 
    {
        try{
            await studentsAPI.remove(id);
            loadStudents();
        }
        catch (err){
            alert(`No se puede eliminar al estudiante porque tiene cursos asociados`);
        }
    } 
    catch (err) 
    {
        console.error('Error al borrar:', err.message);
        alert("Error al borrar.");
    }
}
