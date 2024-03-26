function calculateMortgage() {

    const principal = parseFloat(document.getElementById('principal').value);
    const yearlyInterestRate = parseFloat(document.getElementById('interestRate').value);
    const loanTermMonths = parseInt(document.getElementById('loanTerm').value, 10);

  
    if (isNaN(principal) || principal <= 0 ||
        isNaN(yearlyInterestRate) || yearlyInterestRate <= 0 || yearlyInterestRate > 1 ||
        isNaN(loanTermMonths) || loanTermMonths <= 0) {
        alert('Please enter valid positive numbers.');
        return;
    }


    const monthlyInterestRate = yearlyInterestRate / 12;
    const monthlyPayment = principal * monthlyInterestRate / (1 - Math.pow(1 / (1 + monthlyInterestRate), loanTermMonths));
    const totalPayments = monthlyPayment * loanTermMonths;
    const totalInterest = totalPayments - principal;


    document.getElementById('monthlyPayment').textContent = monthlyPayment.toFixed(2);
    document.getElementById('totalPayments').textContent = totalPayments.toFixed(2);
    document.getElementById('totalInterest').textContent = totalInterest.toFixed(2);
}
