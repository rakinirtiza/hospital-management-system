function addMedicineRow() {
    const container = document.getElementById('medicines-container');
    const row = document.createElement('div');
    row.className = 'medicine-row';
    row.innerHTML = `
        <input type="text" name="medicine_name[]" placeholder="Medicine Name" required>
        <input type="text" name="medicine_dosage[]" placeholder="Dosage" required>
        <input type="text" name="medicine_frequency[]" placeholder="Frequency" required>
        <input type="text" name="medicine_duration[]" placeholder="Duration" required>
        <input type="text" name="medicine_instruction[]" placeholder="Instruction">
        <button type="button" onclick="this.parentNode.remove()">Remove</button>
    `;
    container.appendChild(row);
}
