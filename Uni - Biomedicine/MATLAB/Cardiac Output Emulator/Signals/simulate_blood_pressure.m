function [systolic_pressure, diastolic_pressure] = simulate_blood_pressure(duration, min_systolic, max_systolic, min_diastolic, max_diastolic, variation_percentage)
    % Generate random baseline (between max and min)
    systolic_baseline = min_systolic + (max_systolic - min_systolic) * rand();
    diastolic_baseline = min_diastolic + (max_diastolic - min_diastolic) * rand();

    % Calculate max and min amplitude of the signal based in the baseline
    max_systolic_amplitude = systolic_baseline + systolic_baseline * variation_percentage/100;
    min_systolic_amplitude = systolic_baseline - systolic_baseline * variation_percentage/100;
    max_diastolic_amplitude = diastolic_baseline + diastolic_baseline * variation_percentage/100;
    min_diastolic_amplitude = diastolic_baseline - diastolic_baseline * variation_percentage/100;

    % Generate random systolic and diastolic pressure values within the specified range
    systolic_pressure = rand(1, duration) * (max_systolic_amplitude - min_systolic_amplitude) + min_systolic_amplitude;
    diastolic_pressure = rand(1, duration) * (max_diastolic_amplitude - min_diastolic_amplitude) + min_diastolic_amplitude;
end
