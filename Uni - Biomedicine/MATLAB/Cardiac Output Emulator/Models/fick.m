%%
% Calculates the cardiac output using fick principle.
%%
function cardiac_output = fick(o2_consuption, o2_arterial_concentration, o2_venous_concentration)
    % Ensure the signals have the same length
    if length(o2_consuption) ~= length(o2_arterial_concentration) || length(o2_consuption) ~= length(o2_venous_concentration)
        error('O2 consuption, O2 arterial concentration, and O2 venous concentration signals must have the same length.');
    end

    % Calculate the cardiac output using the formula: CO = O2C / (O2AC - O2VC)
    cardiac_output = o2_consuption ./ (o2_arterial_concentration - o2_venous_concentration) .* 100;
end
