%%
% Calculates the cardiac output using cardiac output principle.
%%
function cardiac_output = principle(heart_rate, stroke_volume)
    % Ensure the signals have the same length
    if length(heart_rate) ~= length(stroke_volume)
        error('Heart rate, and stroke volume signals must have the same length.');
    end

    % Calculate the cardiac output using the formula: CO = HR * SV
    cardiac_output = heart_rate .* stroke_volume;
end

