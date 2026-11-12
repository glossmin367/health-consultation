// Client-side validation and form interactions
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const symptomsTextarea = document.getElementById('symptoms');
    const charCount = document.getElementById('charCount');
    const form = document.getElementById('consultationForm');
    const inputs = form.querySelectorAll('[required]');
    
    // Character counter for symptoms
    if (symptomsTextarea && charCount) {
        symptomsTextarea.addEventListener('input', function() {
            const max = 500;
            const current = this.value.length;
            charCount.textContent = current;
            
            // Change color when getting close to limit
            if (current >= max) {
                charCount.style.color = 'red';
            } else {
                charCount.style.color = '';
            }
        });
    }
    
    // Real-time validation on field blur
    inputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            validateField(this);
        });
    });
    
    // Form submission validation
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate all fields
            inputs.forEach(function(input) {
                if (!validateField(input)) {
                    isValid = false;
                }
            });
            
            // Additional symptoms length check
            if (symptomsTextarea) {
                if (symptomsTextarea.value.length < 10) {
                    showError(symptomsTextarea, 'Symptoms must be at least 10 characters');
                    isValid = false;
                }
                if (symptomsTextarea.value.length > 500) {
                    showError(symptomsTextarea, 'Symptoms must not exceed 500 characters');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    function validateField(field) {
        const value = field.value.trim();
        const type = field.type || field.name;
        const name = field.name;
        
        // Remove previous error styling and any old error messages
        field.classList.remove('is-invalid');
        field.parentNode.querySelectorAll('.invalid-feedback').forEach(function(el) {
            el.remove();
        });
        
        // Check required
        if (!value) {
            showError(field, 'This field is required');
            return false;
        }
        
        // Full name: letters and spaces only, 3-60 chars
        if (name === 'full_name') {
            const nameRegex = /^[A-Za-z\s]{3,60}$/;
            if (!nameRegex.test(value)) {
                showError(field, 'Name must be 3-60 letters and spaces only');
                return false;
            }
        }
        
        // Email: valid format
        if (name === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                showError(field, 'Please enter a valid email address');
                return false;
            }
        }
        
        // Phone: 10-15 digits, leading + allowed
        if (name === 'phone') {
            const phoneRegex = /^[+]?[0-9]{10,15}$/;
            if (!phoneRegex.test(value)) {
                showError(field, 'Phone must be 10-15 digits, leading + allowed');
                return false;
            }
        }
        
        // Age: whole number 1-120
        if (name === 'age') {
            const age = parseInt(value, 10);
            if (isNaN(age) || age < 1 || age > 120) {
                showError(field, 'Age must be a whole number between 1 and 120');
                return false;
            }
        }
        
        // Gender: validated by select, but check if default is selected
        if (name === 'gender') {
            if (!value || value === '') {
                showError(field, 'Please select a gender');
                return false;
            }
        }
        
        // Consultation type: validated by select
        if (name === 'consultation_type') {
            if (!value || value === '') {
                showError(field, 'Please select a consultation type');
                return false;
            }
        }
        
        // Department: validated by select
        if (name === 'department') {
            if (!value || value === '') {
                showError(field, 'Please select a medical department');
                return false;
            }
        }
        
        // Preferred date: must be today or future
        if (name === 'preferred_date') {
            // Build today's date as YYYY-MM-DD (local time) and compare as strings
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const todayStr = `${yyyy}-${mm}-${dd}`;
            if (value < todayStr) {
                showError(field, 'Preferred date must be today or a future date');
                return false;
            }
        }
        
        // Symptoms: 10-500 chars (additional check)
        if (name === 'symptoms') {
            if (value.length < 10) {
                showError(field, 'Symptoms must be at least 10 characters');
                return false;
            }
            if (value.length > 500) {
                showError(field, 'Symptoms must not exceed 500 characters');
                return false;
            }
        }
        
        // Consent checkbox
        if (name === 'consent') {
            if (!field.checked) {
                showError(field, 'You must consent to submit the form');
                return false;
            }
        }
        
        return true;
    }
    
    function showError(field, message) {
        field.classList.add('is-invalid');
        const feedbackDiv = document.createElement('div');
        feedbackDiv.className = 'invalid-feedback';
        feedbackDiv.textContent = message;
        field.parentNode.appendChild(feedbackDiv);
    }
});