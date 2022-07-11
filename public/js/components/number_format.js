
const formatTwoDecimalPlace = (num) => {
    return (Math.round(num * 100) / 100).toFixed(2)
}

const thousands = (num) => {
    return num.toLocaleString()
}
