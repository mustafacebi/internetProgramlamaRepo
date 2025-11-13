// Data/StudentRepository.cs
using System.Collections.Generic;
using System.Linq;
using OgrenciYonetimApp.Models; // Student modelini kullanabilmek için

namespace OgrenciYonetimApp.Data
{
    public class StudentRepository
    {
        // Veritabanı tablosunu simüle eden statik bir liste
        private static List<Student> _students = new List<Student>
        {
            new Student { Id = 1, Name = "Ayşe Yılmaz", StudentNumber = "S1001", Department = "Bilgisayar Müh." },
            new Student { Id = 2, Name = "Mehmet Kaya", StudentNumber = "S1002", Department = "Endüstri Müh." }
        };

        private static int _nextId = 3; // Yeni öğrenci eklemek için ID sayacı

        // --- READ (Oku) ---
        public List<Student> GetAll()
        {
            return _students.ToList();
        }

        public Student GetById(int id)
        {
            return _students.FirstOrDefault(s => s.Id == id);
        }

        // --- CREATE (Ekle) ---
        public void Add(Student student)
        {
            student.Id = _nextId++;
            _students.Add(student);
        }

        // --- UPDATE (Düzenle) ---
        public void Update(Student updatedStudent)
        {
            var existingStudent = GetById(updatedStudent.Id);
            if (existingStudent != null)
            {
                existingStudent.Name = updatedStudent.Name;
                existingStudent.StudentNumber = updatedStudent.StudentNumber;
                existingStudent.Department = updatedStudent.Department;
            }
        }

        // --- DELETE (Sil) ---
        public void Delete(int id)
        {
            var studentToRemove = GetById(id);
            if (studentToRemove != null)
            {
                _students.Remove(studentToRemove);
            }
        }
    }
}