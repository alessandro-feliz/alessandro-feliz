%%
% Calculates the cardiac output using estimation method.
%%
function cardiac_output = estimation(heart_rate, systolic_pressure, diastolic_pressure)
    % Ensure the signals have the same length
    if length(heart_rate) ~= length(systolic_pressure) || length(heart_rate) ~= length(diastolic_pressure)
        error('Heart rate, systolic pressure, and diastolic pressure signals must have the same length.');
    end

    % Calculate the cardiac output using the formula: CO = 2 * (SBP - DBP) * HR
    cardiac_output = 2 * (systolic_pressure - diastolic_pressure) .* heart_rate;
end
